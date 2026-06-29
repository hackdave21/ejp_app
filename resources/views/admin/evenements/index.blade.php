@extends('layouts.admin')
@section('title', 'Événements')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold dark:text-white">Événements</h1>
    <button onclick="openModal('createEventModal')" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
        <i class="fas fa-plus mr-2"></i>Nouvel Événement
    </button>
</div>
@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg">{{ session('success') }}</div>
@endif
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach ($evenements as $event)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
        <div class="flex items-center justify-between mb-2">
            <span class="px-2 py-1 text-xs rounded-full 
                @if($event->date_debut > now()) bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400
                @elseif($event->date_fin < now()) bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400
                @else bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400
                @endif">
                @if($event->date_debut > now()) À venir
                @elseif($event->date_fin < now()) Terminé
                @else En cours
                @endif
            </span>
            <form method="POST" action="{{ route('admin.evenements.destroy', $event) }}" onsubmit="return confirm('Supprimer ?')">
                @csrf @method('DELETE')
                <button type="submit" class="p-1 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded"><i class="fas fa-trash"></i></button>
            </form>
        </div>
        <h3 class="font-semibold dark:text-white">{{ $event->titre }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $event->type }}</p>
        <div class="mt-3 text-sm text-gray-600 dark:text-gray-400 space-y-1">
            <p><i class="fas fa-calendar mr-2"></i>{{ $event->date_debut->format('d/m/Y H:i') }} — {{ $event->date_fin->format('d/m/Y H:i') }}</p>
            <p><i class="fas fa-location-dot mr-2"></i>{{ $event->lieu }}</p>
            <p><i class="fas fa-users mr-2"></i>{{ $event->nombre_participants }} participants</p>
        </div>
    </div>
    @endforeach
</div>

<div id="createEventModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-lg mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold dark:text-white">Nouvel Événement</h3>
            <button onclick="closeModal('createEventModal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.evenements.store') }}">
            @csrf
            <div class="space-y-4">
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Titre</label><input type="text" name="titre" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Type</label>
                    <select name="type" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white">
                        <option value="concert">Concert</option>
                        <option value="retraite_spirituelle">Retraite Spirituelle</option>
                        <option value="evangelisation">Évangélisation</option>
                        <option value="seminaire">Séminaire</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Date début</label><input type="datetime-local" name="date_debut" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                    <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Date fin</label><input type="datetime-local" name="date_fin" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                </div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Lieu</label><input type="text" name="lieu" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Capacité max</label><input type="number" name="capacite_max" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Description</label><textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></textarea></div>
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="closeModal('createEventModal')" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">Annuler</button>
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
