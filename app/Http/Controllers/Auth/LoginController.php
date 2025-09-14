<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if user is inactive
            if ($user->status === 'inactive') {
                Auth::logout();
                return redirect('/')->withErrors(['login' => 'Your account is inactive. Please contact support.']);
            }

            // Redirect based on role
            switch ($user->role) {
                case 'admin':
                    return redirect('/admin/dashboard');
                case 'instructor':
                    return redirect('/instructor/dashboard');
                case 'cashier':
                    return redirect('/cashier/dashboard');
                case 'student':
                    return redirect('/student/dashboard');  // Fixed: Changed to consistent path-based redirect
                default:
                    Auth::logout();
                    return redirect('/')->withErrors(['login' => 'Invalid role.']);
            }
        }

        return redirect('/')->withErrors(['username' => 'Invalid username or password.']);
    }
}

