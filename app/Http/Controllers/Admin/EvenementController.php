<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class EvenementController extends Controller
{
    public function index()
    {
        $evenements = Evenement::latest()->get();
        return view('admin.evenements.index', compact('evenements'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'lieu' => 'required|string|max:255',
            'capacite_max' => 'nullable|integer',
            'description' => 'nullable|string',
        ]);

        $data['user_id'] = auth()->id();
        $evenement = Evenement::create($data);

        NotificationService::log(auth()->id(), 'evenement_cree', 'a créé un événement', $evenement->titre);

        $membres = User::where('role', 'membre')->get();
        foreach ($membres as $membre) {
            NotificationService::notify(
                $membre->id,
                'evenements',
                "Nouvel événement : {$evenement->titre}",
                "Un nouvel événement a été créé : {$evenement->titre} le {$evenement->date_debut->format('d/m/Y')} à {$evenement->lieu}.",
                '/evenements'
            );
        }

        return redirect()->route('admin.evenements.index')->with('success', 'Événement créé.');
    }

    public function show(Evenement $evenement)
    {
        return response()->json($evenement);
    }

    public function update(Request $request, Evenement $evenement)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'lieu' => 'required|string|max:255',
            'capacite_max' => 'nullable|integer',
            'description' => 'nullable|string',
        ]);

        $evenement->update($data);

        return redirect()->route('admin.evenements.index')->with('success', 'Événement mis à jour.');
    }

    public function destroy(Evenement $evenement)
    {
        $evenement->delete();
        return redirect()->route('admin.evenements.index')->with('success', 'Événement supprimé.');
    }
}
