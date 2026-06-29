<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
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
        Evenement::create($data);

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
