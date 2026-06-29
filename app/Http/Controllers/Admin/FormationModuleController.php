<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormationModule;
use Illuminate\Http\Request;

class FormationModuleController extends Controller
{
    public function index()
    {
        $modules = FormationModule::orderBy('ordre')->get();
        return view('admin.formations.index', compact('modules'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'categorie' => 'required|string',
            'icone' => 'nullable|string|max:100',
            'video_url' => 'nullable|url',
            'duree' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'ordre' => 'nullable|integer',
        ]);

        FormationModule::create($data);

        return redirect()->route('admin.formations.index')->with('success', 'Module créé.');
    }

    public function update(Request $request, FormationModule $formation)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'categorie' => 'required|string',
            'icone' => 'nullable|string|max:100',
            'video_url' => 'nullable|url',
            'duree' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'ordre' => 'nullable|integer',
        ]);

        $formation->update($data);

        return redirect()->route('admin.formations.index')->with('success', 'Module mis à jour.');
    }

    public function destroy(FormationModule $formation)
    {
        $formation->delete();
        return redirect()->route('admin.formations.index')->with('success', 'Module supprimé.');
    }
}
