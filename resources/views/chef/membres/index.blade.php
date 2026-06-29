@extends('layouts.chef')
@section('title', 'Membres du Groupe')
@section('content')
<h1 class="text-2xl font-bold mb-6 dark:text-white">Membres de mon groupe</h1>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold">Disciple</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Niveau</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Téléphone</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Date entrée</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach ($membres as $membre)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-chef/10 flex items-center justify-center text-chef font-semibold">
                            {{ strtoupper(substr($membre->prenom, 0, 1)) }}{{ strtoupper(substr($membre->nom, 0, 1)) }}
                        </div>
                        <span class="font-medium dark:text-white">{{ $membre->full_name }}</span>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs rounded-full
                        @if($membre->statut === 'nouveau_membre') bg-blue-100 text-blue-600 dark:bg-blue-900/30
                        @elseif($membre->statut === 'star') bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30
                        @elseif($membre->statut === 'pilote') bg-green-100 text-green-600 dark:bg-green-900/30
                        @else bg-purple-100 text-purple-600 dark:bg-purple-900/30 @endif">
                        {{ $membre->statut }}
                    </span>
                </td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $membre->telephone }}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $membre->date_entree?->format('d/m/Y') ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
