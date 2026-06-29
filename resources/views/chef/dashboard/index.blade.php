@extends('layouts.chef')
@section('title', 'Dashboard Chef')
@section('content')
<h1 class="text-2xl font-bold mb-6 dark:text-white">Dashboard Chef</h1>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg"><i class="fas fa-users text-blue-600 dark:text-blue-400 text-xl"></i></div>
            <div><p class="text-sm text-gray-500 dark:text-gray-400">Membres</p><p class="text-2xl font-bold dark:text-white">{{ $stats['total_membres'] }}</p></div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg"><i class="fas fa-file-lines text-green-600 dark:text-green-400 text-xl"></i></div>
            <div><p class="text-sm text-gray-500 dark:text-gray-400">PV Réunions</p><p class="text-2xl font-bold dark:text-white">{{ $stats['total_reunions'] }}</p></div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg"><i class="fas fa-clock text-red-600 dark:text-red-400 text-xl"></i></div>
            <div><p class="text-sm text-gray-500 dark:text-gray-400">Demandes en attente</p><p class="text-2xl font-bold dark:text-white">{{ $stats['demandes_attente'] }}</p></div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <h2 class="text-lg font-semibold mb-4 dark:text-white">Mes Groupes</h2>
        <div class="space-y-3">
            @foreach ($groupes as $groupe)
            <div class="p-3 border border-gray-100 dark:border-gray-700 rounded-lg">
                <div class="flex items-center justify-between">
                    <p class="font-medium dark:text-white">{{ $groupe->nom }}</p>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $groupe->membres->count() }} membres</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <h2 class="text-lg font-semibold mb-4 dark:text-white">Demandes récentes</h2>
        <div class="space-y-3">
            @forelse ($demandes as $demande)
            <div class="p-3 border border-gray-100 dark:border-gray-700 rounded-lg">
                <p class="font-medium dark:text-white">{{ $demande->membre->full_name }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $demande->from_level }} → {{ $demande->to_level }}</p>
                <span class="text-xs text-gray-400">{{ $demande->created_at->diffForHumans() }}</span>
            </div>
            @empty
            <p class="text-gray-500 dark:text-gray-400 text-sm">Aucune demande récente.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
