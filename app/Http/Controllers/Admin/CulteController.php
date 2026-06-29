<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Culte;
use Illuminate\Http\Request;

class CulteController extends Controller
{
    public function index()
    {
        $cultes = Culte::latest('date')->get();
        return view('admin.cultes.index', compact('cultes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'type' => 'required|string|max:100',
            'theme' => 'required|string|max:255',
            'orateur' => 'required|string|max:255',
            'hommes' => 'nullable|integer|min:0',
            'femmes' => 'nullable|integer|min:0',
            'enfants' => 'nullable|integer|min:0',
        ]);

        Culte::create($data);

        return redirect()->route('admin.cultes.index')->with('success', 'Culte ajouté.');
    }

    public function updatePresence(Request $request, Culte $culte)
    {
        $data = $request->validate([
            'hommes' => 'required|integer|min:0',
            'femmes' => 'required|integer|min:0',
            'enfants' => 'required|integer|min:0',
        ]);

        $culte->update($data);

        return redirect()->route('admin.cultes.index')->with('success', 'Présence mise à jour.');
    }
}
