<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the users, with optional role filtering.
     */
    public function index(Request $request)
{
    $validRoles = ['admin', 'trainer', 'trainee'];
    $role = $request->input('role');

    $query = User::query();

    if ($role && in_array($role, $validRoles)) {
        $query->where('role', $role);
    }

    $users = $query->latest()->paginate(10)->appends(['role' => $role]);

    return view('admin.users.index', compact('users'));
}
}
