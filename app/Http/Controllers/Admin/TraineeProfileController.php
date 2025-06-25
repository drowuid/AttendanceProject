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
    $user = User::with('absences.module')->findOrFail($id);

    // Get sorting direction from query
    $sort = request('sort', 'desc'); // default is 'desc'

    $absences = Absence::with('module')
        ->where('user_id', $user->id)
        ->orderBy('date', $sort)
        ->get();

    $total = $absences->count();
    $justified = $absences->where('justified', true)->count();
    $unjustified = $absences->where('justified', false)->count();


    return view('admin.trainees.profile', compact(
        'user', 'absences', 'total', 'justified', 'unjustified'
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
}
