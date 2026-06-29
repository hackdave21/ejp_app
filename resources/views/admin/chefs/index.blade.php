@extends('layouts.admin')
@section('title', 'Chefs & Groupes')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold dark:text-white">Chefs</h1>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="p-4 border-b border-gray-100 dark:border-gray-700">
            <h2 class="font-semibold dark:text-white">Liste des Chefs</h2>
        </div>
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Nom</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Rôle</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Téléphone</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Groupes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($chefs as $chef)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-4 py-3 font-medium dark:text-white">{{ $chef->user->full_name }}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400">{{ strtoupper($chef->role) }}</span></td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $chef->telephone }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $chef->groupes->pluck('nom')->implode(', ') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
        <h2 class="font-semibold mb-4 dark:text-white">Groupes</h2>
        <div class="grid grid-cols-1 gap-4">
            @foreach ($groupes as $groupe)
            <div class="p-4 border border-gray-100 dark:border-gray-700 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-medium dark:text-white">{{ $groupe->nom }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Chef: {{ $groupe->chef->user->full_name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $groupe->membres->count() }} / {{ $groupe->capacite_max }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
