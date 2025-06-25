<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Absence;
use Illuminate\Routing\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class TraineeProfileController extends Controller
{
    public function show($id)
{
    $user = User::with(['modules.trainer', 'absences.module'])->findOrFail($id);

    $sort = request('sort', 'desc');

    $absences = Absence::with('module')
        ->where('user_id', $user->id)
        ->orderBy('date', $sort)
        ->get();

    $total = $absences->count();
    $justified = $absences->where('justified', true)->count();
    $unjustified = $absences->where('justified', false)->count();

    // ✅ Only modules assigned to this trainee
    $modules = $user->modules ?? collect();

    return view('admin.trainees.profile', compact(
        'user', 'absences', 'total', 'justified', 'unjustified', 'modules'
    ));
}

    public function exportProfilePdf($id)
{
    $trainee = User::with(['course', 'modules', 'absences.module'])
        ->where('role', 'trainee')
        ->findOrFail($id);

    $pdf = Pdf::loadView('admin.trainees.export-profile-pdf', compact('trainee'));

    return $pdf->download("Trainee_Profile_{$trainee->name}.pdf");
}

public function showProfile($id)
{
    $user = User::with(['course', 'modules'])->findOrFail($id);

    $absences = Absence::with('module')
        ->where('user_id', $id)
        ->when(request('sort') === 'asc', fn($q) => $q->orderBy('date', 'asc'))
        ->when(request('sort') === 'desc', fn($q) => $q->orderBy('date', 'desc'))
        ->get();

    $total = $absences->count();
    $justified = $absences->where('justified', true)->count();
    $unjustified = $absences->where('justified', false)->count();

    // Only show modules assigned to this trainee
    $modules = $user->modules ?? collect();

    return view('admin.trainees.profile', compact(
        'user', 'absences', 'total', 'justified', 'unjustified', 'modules'
    ));
}
}
