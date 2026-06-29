@extends('layouts.admin')
@section('title', 'Demandes de Progression')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold dark:text-white">Demandes de Progression</h1>
</div>
@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg">{{ session('error') }}</div>
@endif

@foreach (['en_attente', 'approuvee', 'refusee'] as $statut)
    @if (isset($demandes[$statut]) && $demandes[$statut]->count() > 0)
    <div class="mb-6">
        <h2 class="text-lg font-semibold mb-3 dark:text-white capitalize">{{ $statut === 'en_attente' ? 'En attente' : ($statut === 'approuvee' ? 'Approuvées' : 'Refusées') }}</h2>
        <div class="space-y-3">
            @foreach ($demandes[$statut] as $demande)
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium dark:text-white">{{ $demande->membre->full_name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 dark:bg-gray-700">{{ $demande->from_level }}</span>
                            <i class="fas fa-arrow-right mx-2 text-gray-400"></i>
                            <span class="px-2 py-0.5 text-xs rounded-full bg-primary/10 text-primary">{{ $demande->to_level }}</span>
                        </p>
                        <p class="text-xs text-gray-400 mt-1">{{ $demande->created_at->diffForHumans() }}</p>
                    </div>
                    @if ($statut === 'en_attente')
                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('admin.demandes.approuver', $demande) }}">
                            @csrf @method('PUT')
                            <button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">Approuver</button>
                        </form>
                        <button onclick="openRefusModal({{ $demande->id }})" class="px-3 py-1.5 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">Refuser</button>
                    </div>
                    @endif
                </div>
                @if ($statut === 'refusee' && $demande->motif_refus)
                <div class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg text-sm text-red-600 dark:text-red-400">
                    Motif : {{ $demande->motif_refus }}
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
@endforeach

<form id="refusForm" method="POST" class="hidden">
    @csrf @method('PUT')
    <div id="refusModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold mb-4 dark:text-white">Motif du refus</h3>
            <textarea name="motif_refus" rows="4" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white" placeholder="Expliquez le motif du refus..."></textarea>
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="closeModal('refusModal')" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Confirmer le refus</button>
            </div>
        </div>
    </div>
</form>
<script>
    function openRefusModal(id) {
        document.getElementById('refusForm').action = '/admin/demandes-progression/' + id + '/refuser';
        document.getElementById('refusModal').classList.remove('hidden');
    }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
</script>
@endsection
