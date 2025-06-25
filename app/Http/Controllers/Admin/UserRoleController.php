<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = ['admin', 'trainer', 'trainee'];
        return view('admin.users.edit-role', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,trainer,trainee',
        ]);

        $user->role = $validated['role'];
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User role updated successfully.');
    }
}
