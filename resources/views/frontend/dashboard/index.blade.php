@extends('layouts.frontend')
@section('title', 'Dashboard')
@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm mb-6">
    <h1 class="text-2xl font-bold dark:text-white">Bonjour, {{ auth()->user()->prenom }} !</h1>
    <p class="text-gray-500 dark:text-gray-400 mt-1">Niveau : <strong>{{ auth()->user()->statut }}</strong></p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
        <p class="text-2xl font-bold dark:text-white">{{ $stats['formations_vues'] }}/{{ $stats['formations_total'] }}</p>
        <p class="text-sm text-gray-500 dark:text-gray-400">Formations suivies</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
        <p class="text-2xl font-bold dark:text-white">{{ $stats['evenements_venir'] }}</p>
        <p class="text-sm text-gray-500 dark:text-gray-400">Événements à venir</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
        <p class="text-2xl font-bold dark:text-white">{{ auth()->user()->created_at->diffInDays() }} jours</p>
        <p class="text-sm text-gray-500 dark:text-gray-400">Membre depuis</p>
    </div>
</div>

@if ($evenements->count() > 0)
<div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
    <h2 class="text-lg font-semibold mb-4 dark:text-white">Prochains événements</h2>
    <div class="space-y-3">
        @foreach ($evenements as $event)
        <div class="flex items-center gap-4 p-3 border border-gray-100 dark:border-gray-700 rounded-lg">
            <div class="text-center min-w-[60px]">
                <p class="text-xl font-bold text-primary">{{ $event->date_debut->format('d') }}</p>
                <p class="text-xs text-gray-500">{{ $event->date_debut->format('M') }}</p>
            </div>
            <div class="flex-1">
                <p class="font-medium dark:text-white">{{ $event->titre }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $event->lieu }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
