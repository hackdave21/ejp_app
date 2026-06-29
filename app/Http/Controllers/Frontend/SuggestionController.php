<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Suggestion;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'categorie' => 'required|string',
            'nom' => 'nullable|string|max:100',
            'contenu' => 'required|string',
        ]);

        $data['user_id'] = auth()->id();
        $data['statut'] = 'nouveau';

        $suggestion = Suggestion::create($data);

        $auteur = $suggestion->nom ?: 'Anonyme';
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            NotificationService::notify(
                $admin->id,
                'systeme',
                'Nouvelle suggestion',
                "$auteur a soumis une suggestion ({$suggestion->categorie}).",
                '/admin/suggestions'
            );
        }

        return back()->with('success', 'Merci pour votre suggestion !');
    }
}
