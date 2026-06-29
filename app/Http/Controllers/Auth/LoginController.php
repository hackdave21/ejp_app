<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showAdminLoginForm()
    {
        return view('admin.login');
    }

    public function showChefLoginForm()
    {
        return view('chef.login');
    }

    public function showMembreLoginForm()
    {
        return view('frontend.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'identifiant' => 'required|string',
            'password' => 'required|string',
        ]);

        $identifiant = $request->input('identifiant');
        $field = filter_var($identifiant, FILTER_VALIDATE_EMAIL) ? 'email' : 'telephone';

        if (Auth::attempt([$field => $identifiant, 'password' => $request->input('password')], $request->boolean('reserver_connecte'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            $user->update(['derniere_connexion' => now()]);

            $redirect = match ($user->role) {
                'admin' => '/admin/dashboard',
                'chef' => '/chef/dashboard',
                default => '/dashboard',
            };

            return redirect()->intended($redirect);
        }

        return back()->withErrors([
            'identifiant' => 'Identifiant ou mot de passe incorrect.',
        ])->onlyInput('identifiant');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
