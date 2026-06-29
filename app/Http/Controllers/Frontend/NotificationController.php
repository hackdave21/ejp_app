<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())->latest()->get();
        return view('frontend.notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        $notification->update(['lue' => true, 'date_lue' => now()]);
        return back();
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())->where('lue', false)
            ->update(['lue' => true, 'date_lue' => now()]);

        return back()->with('success', 'Toutes les notifications marquées comme lues.');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return back();
    }
}
