@extends('layouts.chef')
@section('title', 'Progressions')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold dark:text-white">Demandes de Progression</h1>
    <button onclick="openModal('createDemandeModal')" class="px-4 py-2 bg-chef text-white rounded-lg hover:opacity-90"><i class="fas fa-plus mr-2"></i>Nouvelle demande</button>
</div>
@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg">{{ session('success') }}</div>
@endif

@foreach (['en_attente', 'approuvee', 'refusee'] as $statut)
    @if (isset($demandes[$statut]))
    <div class="mb-6">
        <h2 class="text-lg font-semibold mb-3 dark:text-white capitalize">{{ $statut === 'en_attente' ? 'En attente' : ($statut === 'approuvee' ? 'Approuvées' : 'Refusées') }}</h2>
        <div class="space-y-3">
            @foreach ($demandes[$statut] as $demande)
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium dark:text-white">{{ $demande->membre->full_name }}</p>
                        <p class="text-sm"><span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 dark:bg-gray-700">{{ $demande->from_level }}</span> <i class="fas fa-arrow-right mx-2 text-gray-400"></i> <span class="px-2 py-0.5 text-xs rounded-full bg-primary/10 text-primary">{{ $demande->to_level }}</span></p>
                        <p class="text-xs text-gray-400 mt-1">{{ $demande->created_at->diffForHumans() }}</p>
                    </div>
                    @if ($statut === 'en_attente')
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('chef.demandes.approuver', $demande) }}">@csrf @method('PUT')<button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-lg">Approuver</button></form>
                        <button onclick="openRefusModal({{ $demande->id }})" class="px-3 py-1.5 bg-red-600 text-white text-sm rounded-lg">Refuser</button>
                    </div>
                    @endif
                </div>
                @if ($statut === 'refusee' && $demande->motif_refus)
                <div class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg text-sm text-red-600">{{ $demande->motif_refus }}</div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
@endforeach

<div id="createDemandeModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold mb-4 dark:text-white">Nouvelle demande de progression</h3>
        <form method="POST" action="{{ route('chef.demandes.store') }}">
            @csrf
            <div class="space-y-4">
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Membre</label>
                    <select name="membre_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white">
                        @foreach ($membres as $membre)
                            <option value="{{ $membre->id }}">{{ $membre->full_name }} ({{ $membre->statut }})</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Vers le niveau</label>
                    <select name="to_level" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white">
                        <option value="star">Star</option>
                        <option value="pilote">Pilote</option>
                        <option value="pilier">Pilier</option>
                        <option value="missionnaire">Missionnaire</option>
                    </select>
                </div>
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="closeModal('createDemandeModal')" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-chef text-white rounded-lg">Soumettre</button>
            </div>
        </form>
    </div>
</div>

<form id="refusForm" method="POST" class="hidden">
    @csrf @method('PUT')
    <div id="refusModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold mb-4 dark:text-white">Motif du refus</h3>
            <textarea name="motif_refus" rows="4" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white" placeholder="Expliquez le motif..."></textarea>
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="closeModal('refusModal')" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg">Confirmer</button>
            </div>
        </div>
    </div>
</form>

<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
    function openRefusModal(id) {
        document.getElementById('refusForm').action = '/chef/demandes-progression/' + id + '/refuser';
        document.getElementById('refusModal').classList.remove('hidden');
    }
</script>
@endsection
