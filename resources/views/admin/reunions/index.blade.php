@extends('layouts.admin')
@section('title', 'Réunions')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold dark:text-white">Procès-Verbaux</h1>
    <button onclick="openModal('createReunionModal')" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark"><i class="fas fa-plus mr-2"></i>Nouveau PV</button>
</div>
@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg">{{ session('success') }}</div>
@endif
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold">Titre</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Type</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Date</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Statut</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Soumis par</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach ($reunions as $reunion)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3 font-medium dark:text-white">{{ $reunion->titre }}</td>
                <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">{{ $reunion->type }}</span></td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $reunion->date->format('d/m/Y') }}</td>
                <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full {{ $reunion->statut === 'archive' ? 'bg-gray-100 text-gray-600' : 'bg-yellow-100 text-yellow-600' }} dark:bg-opacity-30">{{ $reunion->statut }}</span></td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $reunion->user->full_name }}</td>
                <td class="px-4 py-3 flex gap-1">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('archiver-{{ $reunion->id }}').submit()" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg"><i class="fas fa-archive"></i></a>
                    <form id="archiver-{{ $reunion->id }}" method="POST" action="{{ route('admin.reunions.archiver', $reunion) }}">@csrf @method('PUT')</form>
                    <form method="POST" action="{{ route('admin.reunions.destroy', $reunion) }}" onsubmit="return confirm('Supprimer ?')">@csrf @method('DELETE')<button type="submit" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg"><i class="fas fa-trash"></i></button></form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="createReunionModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-lg mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold dark:text-white">Nouveau PV</h3>
            <button onclick="closeModal('createReunionModal')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.reunions.store') }}">
            @csrf
            <div class="space-y-4">
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Titre</label><input type="text" name="titre" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Type</label>
                        <select name="type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white">
                            <option value="generale">Générale</option>
                            <option value="coordination">Coordination</option>
                            <option value="urgence">Urgence</option>
                        </select>
                    </div>
                    <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Date</label><input type="date" name="date" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                </div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Contenu</label><textarea name="contenu" rows="5" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></textarea></div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Sujets de prière</label><textarea name="sujets_priere" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></textarea></div>
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="closeModal('createReunionModal')" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 rounded-lg">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">Créer</button>
            </div>
        </form>
    </div>
</div>
<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
</script>
@endsection
