<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chef;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class MembreController extends Controller
{
    public function index()
    {
        $membres = User::where('role', 'membre')->with('chefResponsable.user')->latest()->get();
        $chefs = Chef::with('user')->get();
        return view('admin.membres.index', compact('membres', 'chefs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'telephone' => 'required|string|max:20|unique:users',
            'email' => 'nullable|email|unique:users',
            'password' => 'required|string|min:6',
            'chef_responsable_id' => 'nullable|exists:chefs,id',
            'date_naissance' => 'nullable|date',
        ]);

        $data['password'] = bcrypt($data['password']);
        $data['role'] = 'membre';
        $data['statut'] = 'nouveau_membre';
        $data['date_entree'] = now();

        $membre = User::create($data);

        NotificationService::log(auth()->id(), 'membre_ajoute', 'a ajouté un nouveau membre', $membre->full_name);

        if ($membre->chef_responsable_id) {
            NotificationService::notify(
                $membre->chefResponsable->user_id,
                'systeme',
                'Nouveau membre assigné',
                "{$membre->full_name} vous a été assigné comme membre.",
                '/chef/membres'
            );
        }

        return redirect()->route('admin.membres.index')->with('success', 'Membre créé avec succès.');
    }

    public function show(User $membre)
    {
        $membre->load('chefResponsable.user');
        return response()->json($membre);
    }

    public function update(Request $request, User $membre)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'telephone' => 'required|string|max:20|unique:users,telephone,' . $membre->id,
            'email' => 'nullable|email|unique:users,email,' . $membre->id,
            'chef_responsable_id' => 'nullable|exists:chefs,id',
            'statut' => 'required|string',
            'date_naissance' => 'nullable|date',
        ]);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $membre->update($data);

        return redirect()->route('admin.membres.index')->with('success', 'Membre mis à jour.');
    }

    public function destroy(User $membre)
    {
        $membre->delete();
        return redirect()->route('admin.membres.index')->with('success', 'Membre supprimé.');
    }
}
