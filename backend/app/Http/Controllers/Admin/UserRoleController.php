<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function index()
    {
        return response()->json(User::with('role')->get());
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'roleId' => 'required|integer|exists:roles,id',
        ]);

        $user = User::findOrFail($id);
        $user->roleId = $request->roleId;
        $user->save();

        return response()->json(['message' => 'Role updated successfully']);
    }
}
