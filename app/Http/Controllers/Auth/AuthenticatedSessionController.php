<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // If the user is logged in, log them out and invalidate the session.
        // Auth::logout();
        // request()->session()->invalidate();
        // request()->session()->regenerateToken();

        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        // Always start fresh to avoid stale sessions
        Auth::logout(); // Log out any active session
        $request->session()->invalidate(); // Invalidate session data
        $request->session()->regenerateToken(); // Regenerate CSRF token

        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($request->only('email', 'password'), false)) {
            // Regenerate session to prevent session fixation attacks
            $request->session()->regenerate();

            $user = Auth::user();

            // ✅ Redirect based on role
            if ($user->hasRole('admin')) {
                return redirect()->intended(route('admin.documents.index'));
            }

            // For oed, records, or normal users
            return redirect()->intended(route('documents.index'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Clear the remember me cookie (if any)
        \Cookie::queue(\Cookie::forget(Auth::getRecallerName()));

        return redirect('/');
    }
}
