<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommunicationCampagne;
use Illuminate\Http\Request;

class CommunicationController extends Controller
{
    public function index()
    {
        $campagnes = CommunicationCampagne::latest()->get();
        return view('admin.communications.index', compact('campagnes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'canal' => 'required|string',
            'audience_cible' => 'required|string',
            'contenu' => 'required|string',
            'date_envoi' => 'nullable|date',
        ]);

        $data['user_id'] = auth()->id();
        $data['statut'] = $request->date_envoi ? 'programme' : 'brouillon';
        CommunicationCampagne::create($data);

        return redirect()->route('admin.communications.index')->with('success', 'Campagne créée.');
    }

    public function update(Request $request, CommunicationCampagne $communication)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'canal' => 'required|string',
            'audience_cible' => 'required|string',
            'contenu' => 'required|string',
            'date_envoi' => 'nullable|date',
            'statut' => 'required|string',
        ]);

        $communication->update($data);

        return redirect()->route('admin.communications.index')->with('success', 'Campagne mise à jour.');
    }

    public function destroy(CommunicationCampagne $communication)
    {
        $communication->delete();
        return redirect()->route('admin.communications.index')->with('success', 'Campagne supprimée.');
    }
}
