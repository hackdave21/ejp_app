<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chef;
use App\Models\Groupe;
use App\Models\User;
use Illuminate\Http\Request;

class GroupeController extends Controller
{
    public function index()
    {
        $groupes = Groupe::with('chef.user', 'membres')->latest()->get();
        $chefs = Chef::with('user')->get();
        return view('admin.groupes.index', compact('groupes', 'chefs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:100',
            'chef_id' => 'required|exists:chefs,id',
            'capacite_max' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $groupe = Groupe::create($data);

        if ($request->filled('membres')) {
            $groupe->membres()->attach($request->membres, [
                'date_affectation' => now(),
            ]);
        }

        return redirect()->route('admin.groupes.index')->with('success', 'Groupe créé.');
    }

    public function update(Request $request, Groupe $groupe)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:100',
            'chef_id' => 'required|exists:chefs,id',
            'capacite_max' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $groupe->update($data);
        $groupe->membres()->sync($request->membres ?? []);

        return redirect()->route('admin.groupes.index')->with('success', 'Groupe mis à jour.');
    }

    public function destroy(Groupe $groupe)
    {
        $groupe->delete();
        return redirect()->route('admin.groupes.index')->with('success', 'Groupe supprimé.');
    }
}
