<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActiviteRecente;
use App\Models\DemandeProgression;
use App\Models\Notification;
use Illuminate\Http\Request;

class DemandeProgressionController extends Controller
{
    public function index()
    {
        $demandes = DemandeProgression::with('membre')
            ->latest()
            ->get()
            ->groupBy('statut');

        return view('admin.demandes.index', compact('demandes'));
    }

    public function approuver(DemandeProgression $demande)
    {
        if ($demande->statut !== 'en_attente') {
            return back()->with('error', 'Cette demande a déjà été traitée.');
        }

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
            'message' => "Votre progression vers {$demande->to_level} a été approuvée.",
            'lien' => '/progression',
        ]);

        ActiviteRecente::create([
            'user_id' => auth()->id(),
            'type' => 'validation',
            'cible' => $demande->membre->full_name,
            'action' => "a approuvé une progression vers {$demande->to_level}",
        ]);

        return back()->with('success', 'Demande approuvée.');
    }

    public function refuser(Request $request, DemandeProgression $demande)
    {
        if ($demande->statut !== 'en_attente') {
            return back()->with('error', 'Cette demande a déjà été traitée.');
        }

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
