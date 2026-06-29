<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\ActiviteRecente;
use App\Models\DemandeProgression;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class DemandeProgressionController extends Controller
{
    public function index()
    {
        $chef = auth()->user()->chef;
        $membreIds = $chef->groupes->flatMap(fn($g) => $g->membres->pluck('id'));

        $demandes = DemandeProgression::with('membre')
            ->whereIn('membre_id', $membreIds)
            ->latest()
            ->get()
            ->groupBy('statut');

        $membres = User::whereIn('id', $membreIds)->get();

        return view('chef.demandes.index', compact('demandes', 'membres'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'membre_id' => 'required|exists:users,id',
            'to_level' => 'required|string',
        ]);

        $membre = User::findOrFail($data['membre_id']);

        DemandeProgression::create([
            'membre_id' => $data['membre_id'],
            'from_level' => $membre->statut,
            'to_level' => $data['to_level'],
            'statut' => 'en_attente',
        ]);

        return redirect()->route('chef.demandes.index')->with('success', 'Demande de progression soumise.');
    }

    public function approuver(DemandeProgression $demande)
    {
        $demande->update([
            'statut' => 'approuvee',
            'traite_par_id' => auth()->id(),
            'date_traitement' => now(),
        ]);

        $demande->membre->update(['statut' => $demande->to_level]);

        Notification::create([
            'user_id' => $demande->membre_id,
            'categorie' => 'progression',
            'titre' => 'Progression approuvée',
            'message' => "Félicitations ! Votre progression vers {$demande->to_level} a été approuvée.",
            'lien' => '/progression',
        ]);

        return back()->with('success', 'Demande approuvée.');
    }

    public function refuser(Request $request, DemandeProgression $demande)
    {
        $data = $request->validate(['motif_refus' => 'required|string']);

        $demande->update([
            'statut' => 'refusee',
            'motif_refus' => $data['motif_refus'],
            'traite_par_id' => auth()->id(),
            'date_traitement' => now(),
        ]);

        return back()->with('success', 'Demande refusée.');
    }
}
