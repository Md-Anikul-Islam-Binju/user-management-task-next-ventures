<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:admin,user', // Ensure role is either admin or user
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        // Create the role dynamically based on input
        $role = Role::firstOrCreate(['name' => $request->role]);
        // Assign the role to the user
        $user->roles()->attach($role);

        // Return a successful response
        return response()->json([
            'message' => 'User registered successfully!',
            'user' => $user,
            'role' => $role,
        ]);
    }

    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        // Check if the credentials are correct
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            // Create a new (access token) for the user
            $tokenResult = $user->createToken('MyApp');
            $token = $tokenResult->accessToken;

            // Get the role of the user
            $role = $user->roles()->first();
            $roleName = $role ? $role->name : null;
            $permissions = $role ? $role->permissions->pluck('name') : [];

            // Return response with token, user info, role, and permissions
            return response()->json([
                'token' => $token,
                'user' => $user,
                'role' => $roleName,
                'permissions' => $permissions,
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function userInfo()
    {
        $user = Auth::user();
        // Get the role of the user
        $role = $user->roles()->first();
        $roleName = $role ? $role->name : null;
        // Get the permissions of the user's role
        $permissions = $role ? $role->permissions->pluck('name') : [];
        // Return response with  user info, role, and permissions
        return response()->json([
            'user' => $user,
            'role' => $roleName,
            'permissions' => $permissions,
        ]);
    }




}
