<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use App\Models\FormationModule;
use App\Models\FormationSuivi;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'formations_vues' => FormationSuivi::where('user_id', $user->id)->where('vu', true)->count(),
            'formations_total' => FormationModule::count(),
            'evenements_venir' => Evenement::where('date_debut', '>', now())->count(),
        ];

        $evenements = Evenement::where('date_debut', '>', now())->orderBy('date_debut')->take(3)->get();

        return view('frontend.dashboard.index', compact('stats', 'evenements'));
    }
}
