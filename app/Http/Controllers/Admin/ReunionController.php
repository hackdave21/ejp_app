<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reunion;
use Illuminate\Http\Request;

class ReunionController extends Controller
{
    public function index()
    {
        $reunions = Reunion::with('user', 'groupe')->latest()->get();
        return view('admin.reunions.index', compact('reunions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|string',
            'date' => 'required|date',
            'contenu' => 'required|string',
            'participants' => 'nullable|json',
            'sujets_priere' => 'nullable|string',
            'groupe_id' => 'nullable|exists:groupes,id',
        ]);

        $data['user_id'] = auth()->id();
        Reunion::create($data);

        return redirect()->route('admin.reunions.index')->with('success', 'PV créé.');
    }

    public function show(Reunion $reunion)
    {
        return response()->json($reunion);
    }

    public function update(Request $request, Reunion $reunion)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|string',
            'date' => 'required|date',
            'contenu' => 'required|string',
            'participants' => 'nullable|json',
            'sujets_priere' => 'nullable|string',
            'groupe_id' => 'nullable|exists:groupes,id',
            'statut' => 'sometimes|string',
        ]);

        $reunion->update($data);

        return redirect()->route('admin.reunions.index')->with('success', 'PV mis à jour.');
    }

    public function destroy(Reunion $reunion)
    {
        $reunion->delete();
        return redirect()->route('admin.reunions.index')->with('success', 'PV supprimé.');
    }

    public function archiver(Reunion $reunion)
    {
        $reunion->update(['statut' => 'archive']);
        return redirect()->route('admin.reunions.index')->with('success', 'PV archivé.');
    }
}
