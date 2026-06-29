<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompteController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('frontend.compte.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'telephone' => 'required|string|max:20|unique:users,telephone,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'date_naissance' => 'nullable|date',
        ]);

        $user->update($data);

        return back()->with('success', 'Profil mis à jour.');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        auth()->user()->update(['password' => bcrypt($data['new_password'])]);

        return back()->with('success', 'Mot de passe mis à jour.');
    }

    public function updatePhoto(Request $request)
    {
        $data = $request->validate(['photo' => 'required|image|max:2048']);
        $path = $request->file('photo')->store('photos', 'public');
        auth()->user()->update(['photo' => $path]);

        return back()->with('success', 'Photo mise à jour.');
    }
}
