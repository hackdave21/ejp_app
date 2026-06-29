<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parametre;
use Illuminate\Http\Request;

class ParametreController extends Controller
{
    public function index()
    {
        $params = Parametre::pluck('valeur', 'cle')->toArray();
        return view('admin.parametres.index', compact('params'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $cle => $valeur) {
            Parametre::where('cle', $cle)->update(['valeur' => $valeur]);
        }

        return redirect()->route('admin.parametres.index')->with('success', 'Paramètres mis à jour.');
    }
}
