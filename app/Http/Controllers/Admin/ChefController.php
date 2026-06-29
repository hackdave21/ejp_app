<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chef;
use App\Models\Groupe;
use App\Models\User;
use Illuminate\Http\Request;

class ChefController extends Controller
{
    public function index()
    {
        $chefs = Chef::with('user', 'groupes', 'membres')->latest()->get();
        $groupes = Groupe::with('chef.user')->get();
        return view('admin.chefs.index', compact('chefs', 'groupes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'telephone' => 'required|string|max:20|unique:users',
            'email' => 'nullable|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string',
        ]);

        $user = User::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'telephone' => $data['telephone'],
            'email' => $data['email'] ?? null,
            'password' => bcrypt($data['password']),
            'role' => 'chef',
            'statut' => 'pilier',
            'date_entree' => now(),
        ]);

        Chef::create([
            'user_id' => $user->id,
            'role' => $data['role'],
            'telephone' => $data['telephone'],
        ]);

        return redirect()->route('admin.chefs.index')->with('success', 'Chef créé avec succès.');
    }

    public function update(Request $request, Chef $chef)
    {
        $data = $request->validate([
            'role' => 'required|string',
            'statut' => 'boolean',
        ]);

        $chef->update($data);

        return redirect()->route('admin.chefs.index')->with('success', 'Chef mis à jour.');
    }

    public function destroy(Chef $chef)
    {
        $chef->user()->delete();
        $chef->delete();
        return redirect()->route('admin.chefs.index')->with('success', 'Chef supprimé.');
    }
}
