<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')->latest()->get();
        return view('admin.notifications.index', compact('notifications'));
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'titre' => 'required|string|max:255',
            'message' => 'required|string',
            'categorie' => 'required|string',
            'lien' => 'nullable|string',
        ]);

        Notification::create($data);

        return redirect()->route('admin.notifications.index')->with('success', 'Notification envoyée.');
    }
}
