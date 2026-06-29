@extends('layouts.admin')
@section('title', 'Groupes')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold dark:text-white">Groupes</h1>
    <button onclick="openModal('createGroupeModal')" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark"><i class="fas fa-plus mr-2"></i>Nouveau Groupe</button>
</div>
@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg">{{ session('success') }}</div>
@endif
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach ($groupes as $groupe)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold dark:text-white">{{ $groupe->nom }}</h3>
            <form method="POST" action="{{ route('admin.groupes.destroy', $groupe) }}" onsubmit="return confirm('Supprimer ?')">@csrf @method('DELETE')<button type="submit" class="p-1 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded"><i class="fas fa-trash"></i></button></form>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Chef: {{ $groupe->chef->user->full_name }}</p>
        <div class="flex items-center gap-2 mb-2">
            <div class="flex-1 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                <div class="bg-primary rounded-full h-2" style="width: {{ $groupe->membres->count() / max($groupe->capacite_max, 1) * 100 }}%"></div>
            </div>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $groupe->membres->count() }}/{{ $groupe->capacite_max }}</span>
        </div>
    </div>
    @endforeach
</div>

<div id="createGroupeModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-lg mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold dark:text-white">Nouveau Groupe</h3>
            <button onclick="closeModal('createGroupeModal')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.groupes.store') }}">
            @csrf
            <div class="space-y-4">
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Nom</label><input type="text" name="nom" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Chef responsable</label>
                    <select name="chef_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white">
                        @foreach ($chefs as $chef)
                            <option value="{{ $chef->id }}">{{ $chef->user->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Capacité max</label><input type="number" name="capacite_max" value="50" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="closeModal('createGroupeModal')" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 rounded-lg">Annuler</button>
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
