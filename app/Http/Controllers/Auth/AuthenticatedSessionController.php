<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $roleBasedRedirect = match (Auth::user()->role) {
            'admin'    => route('admin.dashboard'),
            'facility' => route('facility.dashboard'),
            'user'     => route('dashboard'),
            default    => route('login'),
        };

        $intendedUrl = $request->session()->pull('url.intended');

        // Intended URL sirf tab honor karo jab wo kisi generic/dusre role ke
        // dashboard route se match na kare — warna role-based redirect hi sahi hai.
        $genericDashboardUrls = [
            route('dashboard'),
            route('facility.dashboard'),
            route('admin.dashboard'),
        ];

        if ($intendedUrl && !in_array($intendedUrl, $genericDashboardUrls)) {
            return redirect($intendedUrl);
        }

        return redirect($roleBasedRedirect);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}