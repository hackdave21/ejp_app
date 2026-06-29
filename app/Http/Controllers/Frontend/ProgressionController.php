<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DemandeProgression;
use App\Models\FormationSuivi;
use Illuminate\Http\Request;

class ProgressionController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $levels = [
            'nouveau_membre' => ['label' => 'Nouveau Membre', 'icon' => 'fa-user-plus', 'order' => 0],
            'star' => ['label' => 'Star', 'icon' => 'fa-star', 'order' => 1],
            'pilote' => ['label' => 'Pilote', 'icon' => 'fa-plane', 'order' => 2],
            'pilier' => ['label' => 'Pilier', 'icon' => 'fa-building-columns', 'order' => 3],
            'missionnaire' => ['label' => 'Missionnaire', 'icon' => 'fa-globe', 'order' => 4],
        ];

        $currentOrder = $levels[$user->statut]['order'] ?? 0;
        $nextLevel = collect($levels)->firstWhere('order', $currentOrder + 1);

        $formations = FormationSuivi::where('user_id', $user->id)->with('module')->get();
        $allModulesCount = \App\Models\FormationModule::count();
        $formationsProgress = $allModulesCount > 0
            ? round($formations->where('vu', true)->count() / $allModulesCount * 100)
            : 0;

        $demandes = DemandeProgression::where('membre_id', $user->id)->latest()->get();

        return view('frontend.progression.index', compact(
            'user', 'levels', 'currentOrder', 'nextLevel',
            'formations', 'formationsProgress', 'allModulesCount', 'demandes'
        ));
    }

    public function demanderProgression(Request $request)
    {
        $user = auth()->user();

        $levels = ['nouveau_membre' => 0, 'star' => 1, 'pilote' => 2, 'pilier' => 3, 'missionnaire' => 4];
        $currentOrder = $levels[$user->statut] ?? 0;
        $nextLevel = array_search($currentOrder + 1, $levels);

        if (!$nextLevel) {
            return back()->with('error', 'Vous avez atteint le niveau maximum.');
        }

        DemandeProgression::create([
            'membre_id' => $user->id,
            'from_level' => $user->statut,
            'to_level' => $nextLevel,
            'statut' => 'en_attente',
        ]);

        return back()->with('success', 'Votre demande de progression a été soumise.');
    }
}
