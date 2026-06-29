@extends('layouts.admin')
@section('title', 'Cultes')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold dark:text-white">Cultes</h1>
    <button onclick="openModal('createCulteModal')" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
        <i class="fas fa-plus mr-2"></i>Nouveau Culte
    </button>
</div>
@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg">{{ session('success') }}</div>
@endif
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Date & Type</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Thème</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Orateur</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Présence</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach ($cultes as $culte)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3">
                    <span class="font-medium dark:text-white">{{ $culte->date->format('d/m/Y') }}</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">{{ $culte->type }}</span>
                </td>
                <td class="px-4 py-3 dark:text-white">{{ $culte->theme }}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $culte->orateur }}</td>
                <td class="px-4 py-3">
                    <span class="font-medium dark:text-white">{{ $culte->total }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">(H:{{ $culte->hommes }} F:{{ $culte->femmes }} E:{{ $culte->enfants }})</span>
                </td>
                <td class="px-4 py-3">
                    <button onclick="openPresenceModal({{ $culte->id }}, {{ $culte->hommes }}, {{ $culte->femmes }}, {{ $culte->enfants }})" class="p-2 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg">
                        <i class="fas fa-check"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="createCulteModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-lg mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold dark:text-white">Nouveau Culte</h3>
            <button onclick="closeModal('createCulteModal')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.cultes.store') }}">
            @csrf
            <div class="space-y-4">
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Date</label><input type="date" name="date" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Type</label><input type="text" name="type" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Thème</label><input type="text" name="theme" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Orateur</label><input type="text" name="orateur" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="closeModal('createCulteModal')" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 rounded-lg">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">Créer</button>
            </div>
        </form>
    </div>
</div>

<form id="presenceForm" method="POST" class="hidden">
    @csrf @method('PUT')
    <div id="presenceModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold mb-4 dark:text-white">Point de Présence</h3>
            <div class="space-y-4">
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Hommes</label><input type="number" name="hommes" id="presenceHommes" required min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Femmes</label><input type="number" name="femmes" id="presenceFemmes" required min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Enfants</label><input type="number" name="enfants" id="presenceEnfants" required min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="closeModal('presenceModal')" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">Enregistrer</button>
            </div>
        </div>
    </div>
</form>

<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
    function openPresenceModal(id, hommes, femmes, enfants) {
        document.getElementById('presenceHommes').value = hommes;
        document.getElementById('presenceFemmes').value = femmes;
        document.getElementById('presenceEnfants').value = enfants;
        document.getElementById('presenceForm').action = '/admin/cultes/' + id + '/presence';
        document.getElementById('presenceModal').classList.remove('hidden');
    }
</script>
@endsection
