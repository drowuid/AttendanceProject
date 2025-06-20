<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\User;
use App\Models\Module;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class AbsenceController extends Controller
{
    public function index(Request $request)
{
    $query = Absence::with(['user', 'module']);

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
    $modules = Module::all();

    return view('admin.absences.index', compact('absences', 'users', 'modules'));
}


    public function show(Absence $absence)
    {
        return view('admin.absences.show', compact('absence'));
    }

    public function edit(Absence $absence)
    {
        $users = User::all();
        $modules = Module::all();
        return view('admin.absences.edit', compact('absence', 'users', 'modules'));
    }

    public function update(Request $request, Absence $absence)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'module_id' => 'required|exists:modules,id',
            'date' => 'required|date',
            'reason' => 'nullable|string',
        ]);

        $absence->update($validated);
        $user = Auth::user();
        $route = ($user && isset($user->role) && $user->role === 'admin') ? 'admin.absences.index' : 'trainer.absences.index';
        return redirect()->route($route)
            ->with('success', 'Absence updated successfully.');
    }

    public function destroy(Absence $absence)
{
    $absence->delete();
    return redirect()->route('admin.absences.index')->with('success', 'Absence deleted.');
}

public function trash()
{
    $absences = Absence::onlyTrashed()->latest()->paginate(10);
    return view('admin.absences.trash', compact('absences'));
}

public function restore($id)
{
    Absence::onlyTrashed()->findOrFail($id)->restore();
    return redirect()->route('admin.absences.trash')->with('success', 'Absence restored.');
}

public function forceDelete($id)
{
    Absence::onlyTrashed()->findOrFail($id)->forceDelete();
    return redirect()->route('admin.absences.trash')->with('success', 'Absence permanently deleted.');
}

public function export(Request $request)
{
    $query = Absence::with(['user', 'module']);

    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    if ($request->filled('module_id')) {
        $query->where('module_id', $request->module_id);
    }

    if ($request->filled('search')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });
    }

    $absences = $query->get();

    $pdf = Pdf::loadView('admin.absences.pdf', compact('absences'));

    return $pdf->download('absences_report.pdf');
}

public function stats()
{
    $modules = Module::with(['attendances.user'])->get();

    $stats = [];

    foreach ($modules as $module) {
        $traineeStats = [];
        foreach ($module->attendances->groupBy('user_id') as $records) {
            $user = $records->first()->user;
            $total = $records->count();
            $excused = $records->where('is_excused', true)->count();
            $unexcused = $records->where('is_excused', false)->count();

            $traineeStats[] = [
                'user' => $user,
                'total' => $total,
                'excused' => $excused,
                'unexcused' => $unexcused,
                'percentage' => $total > 0 ? round(($excused / $total) * 100, 1) : 0,
            ];
        }

        $stats[] = [
            'module' => $module,
            'traineeStats' => $traineeStats
        ];
    }

    return view('admin.absences.stats', [
        'stats' => $stats,
    ]);
}

public function calendar()
{
    $absences = Absence::with(['user', 'module'])->get();

    $events = $absences->map(function ($absence) {
        return [
            'title' => optional($absence->user)->name . ' - ' . optional($absence->module)->name,
            'start' => $absence->date,
            'url'   => route('admin.absences.show', $absence->id),
            'color' => $absence->is_excused ? '#34d399' : '#f87171', // green = excused, red = unexcused
        ];
    });

    return view('admin.absences.calendar', [
        'events' => $events
    ]);
}





}
