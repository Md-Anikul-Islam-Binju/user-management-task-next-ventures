<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    // Fetch all roles
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    // Create a new role
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|unique:roles|max:255',
        ]);

        // Create the role
        $role = Role::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Role created successfully!',
            'role' => $role,
        ]);
    }


    public function assignRoles(Request $request, $userId)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id', // Ensure role exists
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the user
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Check if the role is already assigned
        $existingRole = $user->roles()->where('role_id', $request->role_id)->exists();
        if ($existingRole) {
            return response()->json(['message' => 'Role is already assigned to the user.'], 400);
        }

        // Assign the role to the user
        $user->roles()->attach($request->role_id);

        // Reload the user with roles
        $userWithRoles = User::where('id', $userId)->with('roles')->first();

        return response()->json([
            'message' => 'Role assigned successfully!',
            'user' => $userWithRoles,
        ]);
    }

}
