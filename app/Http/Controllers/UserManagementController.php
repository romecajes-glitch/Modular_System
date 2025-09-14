<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserManagementController extends Controller
{
    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Debug logging
        Log::info('UserManagementController store method called');
        Log::info('Request data:', $request->all());

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:student,instructor,cashier',
            'birthdate' => 'required|date',
            'program_handled' => 'required_if:role,instructor|exists:programs,id',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Generate username as firstname + lastname + suffix (lowercase, no spaces)
        $username = strtolower($request->first_name . $request->last_name . ($request->suffix_name ? $request->suffix_name : ''));

        // Concatenate first name, middle name, last name, and suffix name for display name
        $fullName = trim($request->first_name . ' ' .
                      ($request->middle_name ? $request->middle_name . ' ' : '') .
                      $request->last_name .
                      ($request->suffix_name ? ' ' . $request->suffix_name : ''));

        $user = User::create([
            'name' => $fullName,
            'username' => $username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'active',
            'middle_name' => $request->middle_name,
            'suffix_name' => $request->suffix_name,
            'birthdate' => $request->birthdate,
            'program_id' => $request->role === 'instructor' ? $request->program_handled : null,
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'credentials' => [
                'username' => $username,
                'password' => $request->password // Plain text password for display
            ]
        ], 201);
    }
}
