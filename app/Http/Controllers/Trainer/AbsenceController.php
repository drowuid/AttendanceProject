<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AbsenceController extends Controller
{
    public function index(Request $request)
{
    $trainerId = auth()->id();

    $query = Absence::whereHas('module', function ($q) use ($trainerId) {
        $q->where('trainer_id', $trainerId);
    })->with(['user', 'module']);

    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    if ($request->filled('module_id')) {
        $query->where('module_id', $request->module_id);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('user', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        });
    }

    $absences = $query->latest()->paginate(10);
    $users = User::all();
    $modules = Module::where('trainer_id', $trainerId)->get();

    return view('trainer.absences.index', compact('absences', 'users', 'modules'));
}


    public function show(Absence $absence)
    {
        $this->authorizeAccess($absence);
        return view('trainer.absences.show', compact('absence'));
    }

    public function edit(Absence $absence)
    {
        $this->authorizeAccess($absence);
        $users = User::all();
        $modules = Module::where('trainer_id', Auth::id())->get();
        return view('trainer.absences.edit', compact('absence', 'users', 'modules'));
    }

    public function update(Request $request, Absence $absence)
    {
        $this->authorizeAccess($absence);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'module_id' => 'required|exists:modules,id',
            'date' => 'required|date',
            'reason' => 'nullable|string',
        ]);

        $absence->update($validated);

    return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.absences.index' : 'trainer.absences.index')
        ->with('success', 'Absence updated successfully.');
    }

    public function destroy(Absence $absence)
{
    $absence->delete();
    return redirect()->route('trainer.absences.index')->with('success', 'Absence deleted.');
}


    private function authorizeAccess(Absence $absence)
    {
        if ($absence->module->trainer_id !== Auth::id()) {
            abort(403);
        }
    }

    public function trash()
{
    $absences = Absence::onlyTrashed()->whereHas('module', function ($query) {
        $query->where('trainer_id', auth()->id());
    })->latest()->paginate(10);

    return view('trainer.absences.trash', compact('absences'));
}

public function restore($id)
{
    Absence::onlyTrashed()->findOrFail($id)->restore();
    return redirect()->route('trainer.absences.trash')->with('success', 'Absence restored.');
}

public function forceDelete($id)
{
    Absence::onlyTrashed()->findOrFail($id)->forceDelete();
    return redirect()->route('trainer.absences.trash')->with('success', 'Absence permanently deleted.');
}

public function calendar()
{
    $absences = Absence::with(['user', 'module'])->get();

    $events = $absences->map(function ($absence) {
        return [
            'title' => $absence->user->name . ' - ' . $absence->module->name,
            'start' => $absence->date,
            'color' => $absence->is_excused ? '#34d399' : '#f87171',
            'url' => route('trainer.absences.show', $absence->id),
        ];
    });

    return view('trainer.absences.calendar', ['events' => $events]);
}

public function stats()
    {
        $trainer = Auth::user();

        // Adjust this depending on your system's trainer-user-module logic
        $absences = Absence::with('user', 'module')
            ->whereHas('module', function ($query) use ($trainer) {
                $query->where('trainer_id', $trainer->id); // adjust if needed
            })
            ->get();

        $totalAbsences = $absences->count();

        $absencesByModule = $absences->groupBy('module.name')->map(function ($group) {
            return $group->count();
        });

        $absencesPerMonth = $absences->groupBy(function ($absence) {
            return \Carbon\Carbon::parse($absence->date)->format('F Y');
        })->map(function ($group) {
            return $group->count();
        });

        return view('trainer.absences.stats', compact(
            'totalAbsences',
            'absencesByModule',
            'absencesPerMonth'
        ));
    }

public function exportAbsenceEmailSummary()
{
    $trainerId = auth()->id();

    $absences = Absence::with(['trainee', 'module'])
        ->whereHas('module', fn($q) => $q->where('trainer_id', $trainerId))
        ->get();

    if ($absences->isEmpty()) {
        return back()->with('error', 'No absence data found.');
    }

    $summary = [];
    $summary[] = "Trainer Absence Summary Report";
    $summary[] = "Generated on: " . now()->format('Y-m-d H:i');
    $summary[] = "----------------------------------------";

    $total = $absences->count();
    $justified = $absences->where('justified', true)->count();
    $unjustified = $total - $justified;

    $summary[] = "Total Absences: $total";
    $summary[] = "Justified: $justified";
    $summary[] = "Unjustified: $unjustified";
    $summary[] = "";

    $byModule = $absences->groupBy('module.name');
    foreach ($byModule as $module => $records) {
        $summary[] = "Module: $module";
        $summary[] = "- Absences: " . $records->count();
        $summary[] = "- Justified: " . $records->where('justified', true)->count();
        $summary[] = "- Unjustified: " . $records->where('justified', false)->count();
        $summary[] = "";
    }

    $filename = 'absence_summary_' . now()->format('Ymd_His') . '.txt';
    $filePath = storage_path('app/public/' . $filename);

    file_put_contents($filePath, implode(PHP_EOL, $summary));

    return response()->download($filePath)->deleteFileAfterSend(true);
}



}
