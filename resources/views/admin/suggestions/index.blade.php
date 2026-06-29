@extends('layouts.admin')
@section('title', 'Suggestions')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold dark:text-white">Suggestions</h1>
</div>
@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg">{{ session('success') }}</div>
@endif
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
        <p class="text-2xl font-bold dark:text-white">{{ $suggestions->count() }}</p>
        <p class="text-sm text-gray-500 dark:text-gray-400">Toutes</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
        <p class="text-2xl font-bold dark:text-white">{{ $suggestions->where('categorie', 'eglise')->count() }}</p>
        <p class="text-sm text-gray-500 dark:text-gray-400">Pour l'Église</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
        <p class="text-2xl font-bold dark:text-white">{{ $suggestions->where('categorie', 'plateforme')->count() }}</p>
        <p class="text-sm text-gray-500 dark:text-gray-400">Pour la Plateforme</p>
    </div>
</div>
<div class="space-y-4">
    @forelse ($suggestions as $suggestion)
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2 py-1 text-xs rounded-full {{ $suggestion->categorie === 'eglise' ? 'bg-purple-100 text-purple-600 dark:bg-purple-900/30' : 'bg-teal-100 text-teal-600 dark:bg-teal-900/30' }}">
                        {{ $suggestion->categorie === 'eglise' ? 'Église EJP' : 'Plateforme Web' }}
                    </span>
                    <span class="px-2 py-1 text-xs rounded-full {{ $suggestion->statut === 'nouveau' ? 'bg-red-100 text-red-600' : ($suggestion->statut === 'lu' ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600') }} dark:bg-opacity-30">{{ $suggestion->statut }}</span>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">De {{ $suggestion->nom ?? 'Anonyme' }} • {{ $suggestion->created_at->diffForHumans() }}</p>
                <p class="dark:text-white">{{ $suggestion->contenu }}</p>
            </div>
            <div class="flex items-center gap-2 ml-4">
                @if ($suggestion->statut === 'nouveau')
                <form method="POST" action="{{ route('admin.suggestions.statut', $suggestion) }}">
                    @csrf @method('PUT')
                    <input type="hidden" name="statut" value="lu">
                    <button type="submit" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg" title="Marquer comme lu"><i class="fas fa-check"></i></button>
                </form>
                @endif
                <form method="POST" action="{{ route('admin.suggestions.destroy', $suggestion) }}" onsubmit="return confirm('Supprimer ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <p class="text-gray-500 dark:text-gray-400 text-center py-8">Aucune suggestion pour le moment.</p>
    @endforelse
</div>
@endsection
