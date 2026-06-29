@extends('layouts.frontend')
@section('title', 'Notifications')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold dark:text-white">Notifications</h1>
    @if ($notifications->where('lue', false)->count() > 0)
    <form method="POST" action="{{ route('membre.notifications.markAllRead') }}">
        @csrf @method('PUT')
        <button type="submit" class="text-sm text-primary hover:underline">Tout marquer comme lu</button>
    </form>
    @endif
</div>
@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg">{{ session('success') }}</div>
@endif
<div class="space-y-3">
    @forelse ($notifications as $notif)
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 flex items-start justify-between {{ !$notif->lue ? 'border-l-4 border-l-primary' : '' }}">
        <div class="flex-1">
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30">{{ $notif->categorie }}</span>
                @if (!$notif->lue)
                    <span class="w-2 h-2 bg-primary rounded-full"></span>
                @endif
            </div>
            <h4 class="font-medium dark:text-white">{{ $notif->titre }}</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $notif->message }}</p>
            <p class="text-xs text-gray-400 mt-2">{{ $notif->created_at->diffForHumans() }}</p>
        </div>
        <div class="flex items-center gap-1 ml-4">
            @if (!$notif->lue)
            <form method="POST" action="{{ route('membre.notifications.markRead', $notif) }}">
                @csrf @method('PUT')
                <button type="submit" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg"><i class="fas fa-check"></i></button>
            </form>
            @endif
            <form method="POST" action="{{ route('membre.notifications.destroy', $notif) }}" onsubmit="return confirm('Supprimer ?')">
                @csrf @method('DELETE')
                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg"><i class="fas fa-trash"></i></button>
            </form>
        </div>
    </div>
    @empty
    <p class="text-center text-gray-500 dark:text-gray-400 py-8">Aucune notification.</p>
    @endforelse
</div>
@endsection
