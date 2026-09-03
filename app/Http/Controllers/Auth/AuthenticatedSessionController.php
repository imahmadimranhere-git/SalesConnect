<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
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

        $user = Auth::user();

        // Block login if the user's own account has been suspended.
        if ($user->status !== 'active') {
            $this->rejectLogin($request, 'Your account has been suspended. Please contact your administrator.');
        }

        // Block login if the user's entire company has been suspended (Admin/Distributor/Shopkeeper).
        if ($user->company && $user->company->status !== 'active') {
            $this->rejectLogin($request, 'Your company account has been suspended. Please contact support.');
        }

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Log the user back out and throw a validation error, as if login had failed.
     */
    protected function rejectLogin(Request $request, string $message): void
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        throw ValidationException::withMessages([
            'email' => $message,
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

        return redirect('/');
    }

    /**
     * Decide which panel to redirect the user to, based on their role.
     */
    protected function redirectPath(): string
    {
        $user = Auth::user();

        return match ($user->role) {
            'super_admin' => '/super-admin/dashboard',
            'admin'       => '/admin/dashboard',
            'distributor' => '/distributor/dashboard',
            'shopkeeper'  => '/shopkeeper/dashboard',
            default       => '/login',
        };
    }
}