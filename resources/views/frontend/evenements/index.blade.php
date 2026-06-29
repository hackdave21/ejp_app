@extends('layouts.frontend')
@section('title', 'Événements')
@section('content')
<h1 class="text-2xl font-bold mb-6 dark:text-white">Événements</h1>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse ($evenements as $event)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
        <span class="px-2 py-1 text-xs rounded-full 
            @if($event->date_debut > now()) bg-blue-100 text-blue-600 dark:bg-blue-900/30
            @elseif($event->date_fin < now()) bg-gray-100 text-gray-600 dark:bg-gray-700
            @else bg-green-100 text-green-600 dark:bg-green-900/30
            @endif">
            @if($event->date_debut > now()) À venir
            @elseif($event->date_fin < now()) Terminé
            @else En cours
            @endif
        </span>
        <h3 class="font-semibold mt-2 dark:text-white">{{ $event->titre }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $event->type }}</p>
        <div class="mt-3 text-sm text-gray-600 dark:text-gray-400 space-y-1">
            <p><i class="fas fa-calendar mr-2"></i>{{ $event->date_debut->format('d/m/Y H:i') }}</p>
            <p><i class="fas fa-location-dot mr-2"></i>{{ $event->lieu }}</p>
        </div>
    </div>
    @empty
    <p class="col-span-3 text-center text-gray-500 dark:text-gray-400 py-8">Aucun événement pour le moment.</p>
    @endforelse
</div>
@endsection
