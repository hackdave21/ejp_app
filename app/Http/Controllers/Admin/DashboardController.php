<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemandeProgression;
use App\Models\Evenement;
use App\Models\FormationModule;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_membres' => User::where('role', 'membre')->count(),
            'total_chefs' => User::where('role', 'chef')->count(),
            'total_evenements' => Evenement::count(),
            'total_formations' => FormationModule::count(),
            'demandes_en_attente' => DemandeProgression::where('statut', 'en_attente')->count(),
            'nouveaux_membres' => User::where('role', 'membre')->where('statut', 'nouveau_membre')->count(),
        ];

        $recentMembers = User::where('role', 'membre')->latest()->take(5)->get();
        $demandes = DemandeProgression::with('membre')->where('statut', 'en_attente')->latest()->take(5)->get();

        return view('admin.dashboard.index', compact('stats', 'recentMembers', 'demandes'));
    }
}
