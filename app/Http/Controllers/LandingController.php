<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\FormationModule;

class LandingController extends Controller
{
    public function index()
    {
        $evenements = Evenement::where('date_debut', '>', now())->orderBy('date_debut')->take(3)->get();
        $formations = FormationModule::where('statut', true)->orderBy('ordre')->take(6)->get();

        return view('accueil', compact('evenements', 'formations'));
    }
}
