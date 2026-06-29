<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\DemandeProgression;
use App\Models\Groupe;
use App\Models\Reunion;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $chef = auth()->user()->chef;
        $groupes = $chef->groupes;
        $membreIds = $groupes->flatMap(fn($g) => $g->membres->pluck('id'));

        $stats = [
            'total_membres' => User::whereIn('id', $membreIds)->count(),
            'total_reunions' => Reunion::where('user_id', auth()->id())->count(),
            'demandes_attente' => DemandeProgression::whereIn('membre_id', $membreIds)
                ->where('statut', 'en_attente')->count(),
        ];

        $membres = User::whereIn('id', $membreIds)->with('chefResponsable.user')->get();
        $demandes = DemandeProgression::with('membre')
            ->whereIn('membre_id', $membreIds)
            ->latest()
            ->take(5)
            ->get();

        return view('chef.dashboard.index', compact('stats', 'membres', 'demandes', 'groupes'));
    }
}
