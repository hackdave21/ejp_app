@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <h1 class="text-2xl font-bold mb-6 dark:text-white">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <i class="fas fa-users text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Membres</p>
                    <p class="text-2xl font-bold dark:text-white">{{ $stats['total_membres'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                    <i class="fas fa-crown text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Chefs</p>
                    <p class="text-2xl font-bold dark:text-white">{{ $stats['total_chefs'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                    <i class="fas fa-calendar text-green-600 dark:text-green-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Événements</p>
                    <p class="text-2xl font-bold dark:text-white">{{ $stats['total_evenements'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                    <i class="fas fa-graduation-cap text-orange-600 dark:text-orange-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Formations</p>
                    <p class="text-2xl font-bold dark:text-white">{{ $stats['total_formations'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg">
                    <i class="fas fa-clock text-red-600 dark:text-red-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">En attente</p>
                    <p class="text-2xl font-bold dark:text-white">{{ $stats['demandes_en_attente'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-teal-100 dark:bg-teal-900/30 rounded-lg">
                    <i class="fas fa-user-plus text-teal-600 dark:text-teal-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nouveaux</p>
                    <p class="text-2xl font-bold dark:text-white">{{ $stats['nouveaux_membres'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-lg font-semibold mb-4 dark:text-white">Membres récents</h2>
            <div class="space-y-3">
                @foreach ($recentMembers as $membre)
                    <div class="flex items-center gap-3 p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-semibold">
                            {{ strtoupper(substr($membre->prenom, 0, 1)) }}{{ strtoupper(substr($membre->nom, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <p class="font-medium dark:text-white">{{ $membre->full_name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $membre->statut }}</p>
                        </div>
                        <span class="text-xs text-gray-400">{{ $membre->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold dark:text-white">Demandes de progression</h2>
                <span class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs rounded-full">{{ $stats['demandes_en_attente'] }} en attente</span>
            </div>
            <div class="space-y-3">
                @forelse ($demandes as $demande)
                    <div class="p-3 border border-gray-100 dark:border-gray-700 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <p class="font-medium dark:text-white">{{ $demande->membre->full_name }}</p>
                            <span class="text-xs text-gray-400">{{ $demande->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $demande->from_level }} → {{ $demande->to_level }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Aucune demande en attente.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
