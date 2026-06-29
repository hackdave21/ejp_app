<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\User;

class MembreController extends Controller
{
    public function index()
    {
        $chef = auth()->user()->chef;
        $membreIds = $chef->groupes->flatMap(fn($g) => $g->membres->pluck('id'));
        $membres = User::whereIn('id', $membreIds)->with('chefResponsable.user', 'formationsSuivi.module')->get();

        return view('chef.membres.index', compact('membres'));
    }

    public function show(User $membre)
    {
        $membre->load('chefResponsable.user', 'formationsSuivi.module');
        return response()->json($membre);
    }
}
