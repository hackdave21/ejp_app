<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\Reunion;
use Illuminate\Http\Request;

class ReunionController extends Controller
{
    public function index()
    {
        $reunions = Reunion::where('user_id', auth()->id())->latest()->get();
        return view('chef.reunions.index', compact('reunions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'date' => 'required|date',
            'contenu' => 'required|string',
            'participants' => 'nullable|json',
            'sujets_priere' => 'nullable|string',
        ]);

        $data['user_id'] = auth()->id();
        $data['type'] = 'generale';
        $data['statut'] = 'brouillon';

        Reunion::create($data);

        return redirect()->route('chef.reunions.index')->with('success', 'PV créé.');
    }

    public function show(Reunion $reunion)
    {
        return response()->json($reunion);
    }

    public function update(Request $request, Reunion $reunion)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'date' => 'required|date',
            'contenu' => 'required|string',
            'participants' => 'nullable|json',
            'sujets_priere' => 'nullable|string',
        ]);

        $reunion->update($data);

        return redirect()->route('chef.reunions.index')->with('success', 'PV mis à jour.');
    }

    public function destroy(Reunion $reunion)
    {
        $reunion->delete();
        return redirect()->route('chef.reunions.index')->with('success', 'PV supprimé.');
    }

    public function soumettre(Reunion $reunion)
    {
        $reunion->update(['statut' => 'archive']);
        return redirect()->route('chef.reunions.index')->with('success', 'PV soumis avec succès.');
    }
}
