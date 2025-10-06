<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->is('dashboard') || $request->is('categories*') || $request->is('products*') || $request->is('orders*')) {
                return redirect('/login')->with('error', 'Only administrators can access this area. Please log in with an admin account.');
            }
            return redirect('/login');
        }

        if (Auth::user()->role !== 'admin') {
            // Logout non-admin users and show message
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')->with('error', 'Only administrators can access this area. Your account does not have admin privileges.');
        }

        return $next($request);
    }
}