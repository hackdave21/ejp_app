@extends('layouts.admin')
@section('title', 'Notifications')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold dark:text-white">Notifications</h1>
    <button onclick="openModal('sendNotifModal')" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark"><i class="fas fa-paper-plane mr-2"></i>Envoyer</button>
</div>
@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg">{{ session('success') }}</div>
@endif
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold">Destinataire</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Titre</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Catégorie</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Lu</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach ($notifications as $notif)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3 dark:text-white">{{ $notif->user->full_name }}</td>
                <td class="px-4 py-3 font-medium dark:text-white">{{ $notif->titre }}</td>
                <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30">{{ $notif->categorie }}</span></td>
                <td class="px-4 py-3">{!! $notif->lue ? '<span class="text-green-600"><i class="fas fa-check-circle"></i></span>' : '<span class="text-gray-400"><i class="fas fa-circle"></i></span>' !!}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $notif->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="sendNotifModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-lg mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold dark:text-white">Envoyer une notification</h3>
            <button onclick="closeModal('sendNotifModal')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.notifications.send') }}">
            @csrf
            <div class="space-y-4">
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Destinataire</label>
                    <select name="user_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white">
                        @foreach (\App\Models\User::whereIn('role', ['membre', 'chef'])->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->full_name }} ({{ $user->role }})</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Catégorie</label>
                    <select name="categorie" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white">
                        <option value="systeme">Système</option>
                        <option value="evenements">Événements</option>
                        <option value="formations">Formations</option>
                        <option value="progression">Progression</option>
                    </select>
                </div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Titre</label><input type="text" name="titre" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Message</label><textarea name="message" rows="3" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></textarea></div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Lien (optionnel)</label><input type="text" name="lien" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="closeModal('sendNotifModal')" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 rounded-lg">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">Envoyer</button>
            </div>
        </form>
    </div>
</div>
<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
</script>
@endsection
