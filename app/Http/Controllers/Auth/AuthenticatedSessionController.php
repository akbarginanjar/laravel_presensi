<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\LogAbsen;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        addJavascriptFile('assets/js/custom/authentication/sign-in/general.js');

        return view('pages/auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {

        $request->authenticate();
        $request->session()->regenerate();
        $user = Auth::user();

        $redirectUrl = $user->type === 'Admin' ? route('dashboard.admin') : route('dashboard');
    
        if ($request->expectsJson()) {
            return response()->json(['redirect' => $redirectUrl], 200);
        }
    
        return redirect()->intended($redirectUrl);

        // return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();

        $user->update(['is_login' => false]);

        if ($user) {
            $logAbsen = LogAbsen::where('user_id', $user->id)
            ->latest('created_at')
            ->first();
    
            if ($logAbsen) {
                $logAbsen->update([
                    'clock_out' => now(),
                ]);
            }
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
