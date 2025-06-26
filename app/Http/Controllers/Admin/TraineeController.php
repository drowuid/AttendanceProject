<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Routing\Controller;

class TraineeController extends Controller
{
    public function index()
{
    $query = User::query()
        ->where('role', 'trainee')
        ->with('course');

    if ($search = request('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%');
        });
    }

    if ($course = request('course')) {
        $query->whereHas('modules', function ($q) use ($course) {
            $q->where('course_id', $course);
        });
    }

    $trainees = $query->orderBy('name')->get();
    $courses = \App\Models\Course::all();

    return view('admin.trainees.index', compact('trainees', 'courses'));
}

public function assignModules($id)
{
    $user = User::where('role', 'trainee')->findOrFail($id);

    // Validate incoming module IDs
    $validated = request()->validate([
        'modules' => 'array',
        'modules.*' => 'exists:modules,id'
    ]);

    // Sync modules
    $user->modules()->sync($validated['modules'] ?? []);

    return redirect()->back()->with('success', 'Modules updated successfully.');
}

}
