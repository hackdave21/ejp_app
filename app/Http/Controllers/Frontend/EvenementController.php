<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Evenement;

class EvenementController extends Controller
{
    public function index()
    {
        $evenements = Evenement::orderBy('date_debut')->get();
        return view('frontend.evenements.index', compact('evenements'));
    }

    public function show(Evenement $evenement)
    {
        return response()->json($evenement);
    }
}
