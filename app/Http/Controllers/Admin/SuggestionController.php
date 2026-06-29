<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Suggestion;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function index()
    {
        $suggestions = Suggestion::latest()->get();
        return view('admin.suggestions.index', compact('suggestions'));
    }

    public function updateStatut(Request $request, Suggestion $suggestion)
    {
        $data = $request->validate(['statut' => 'required|string']);

        if ($data['statut'] === 'lu' && !$suggestion->lu_par_id) {
            $data['lu_par_id'] = auth()->id();
        }

        $suggestion->update($data);

        return redirect()->route('admin.suggestions.index')->with('success', 'Suggestion mise à jour.');
    }

    public function destroy(Suggestion $suggestion)
    {
        $suggestion->delete();
        return redirect()->route('admin.suggestions.index')->with('success', 'Suggestion supprimée.');
    }
}
