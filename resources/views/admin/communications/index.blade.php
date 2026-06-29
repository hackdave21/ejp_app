@extends('layouts.admin')
@section('title', 'Communications')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold dark:text-white">Campagnes de Communication</h1>
    <button onclick="openModal('createCampagneModal')" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark"><i class="fas fa-plus mr-2"></i>Nouvelle Campagne</button>
</div>
@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg">{{ session('success') }}</div>
@endif
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold">Campagne</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Canal</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Cible</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Date</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Statut</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach ($campagnes as $c)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3 font-medium dark:text-white">{{ $c->titre }}</td>
                <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">{{ str_replace('_', ' ', $c->canal) }}</span></td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ str_replace('_', ' ', $c->audience_cible) }}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $c->date_envoi?->format('d/m/Y H:i') ?? '—' }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs rounded-full {{ $c->statut === 'envoye' ? 'bg-green-100 text-green-600' : ($c->statut === 'programme' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600') }} dark:bg-opacity-30">{{ $c->statut }}</span>
                </td>
                <td class="px-4 py-3">
                    <form method="POST" action="{{ route('admin.communications.destroy', $c) }}" onsubmit="return confirm('Supprimer ?')">@csrf @method('DELETE')<button type="submit" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg"><i class="fas fa-trash"></i></button></form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="createCampagneModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-lg mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold dark:text-white">Nouvelle Campagne</h3>
            <button onclick="closeModal('createCampagneModal')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.communications.store') }}">
            @csrf
            <div class="space-y-4">
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Titre</label><input type="text" name="titre" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Canal</label>
                    <select name="canal" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white">
                        <option value="email">Email</option>
                        <option value="notification_push">Notification Push</option>
                        <option value="annonce_in_app">Annonce In-App</option>
                    </select>
                </div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Audience cible</label>
                    <select name="audience_cible" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white">
                        <option value="tous_membres">Tous les membres</option>
                        <option value="tous_chefs">Tous les chefs</option>
                        <option value="groupe_specifique">Groupe spécifique</option>
                    </select>
                </div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Contenu</label><textarea name="contenu" rows="5" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></textarea></div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Date d'envoi (optionnelle)</label><input type="datetime-local" name="date_envoi" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="closeModal('createCampagneModal')" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Annuler</button>
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
