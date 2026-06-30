@extends('layouts.admin')
@section('title', 'Gestion des Demandes de Progression')
@section('page_title', 'Demandes')
@section('content')
@if (session('success'))
    <div class="mb-6 p-4 bg-success/10 border border-success/30 text-success rounded-xl flex items-center gap-3 text-sm font-body">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="mb-6 p-4 bg-danger/10 border border-danger/30 text-danger rounded-xl flex items-center gap-3 text-sm font-body">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

<div class="space-y-8">
    @foreach (['en_attente' => ['label' => 'En attente', 'color' => 'bg-accent/10 text-accent border-accent/30', 'dot' => 'bg-accent'],
                'approuvee' => ['label' => 'Approuvées', 'color' => 'bg-success/10 text-success border-success/30', 'dot' => 'bg-success'],
                'refusee' => ['label' => 'Refusées', 'color' => 'bg-danger/10 text-danger border-danger/30', 'dot' => 'bg-danger']] as $statut => $infos)
        @if (isset($demandes[$statut]) && $demandes[$statut]->count() > 0)
        <div class="animate-fade-in-up">
            <div class="flex items-center gap-3 mb-4">
                <span class="w-2.5 h-2.5 rounded-full {{ $infos['dot'] }}"></span>
                <h2 class="text-lg font-bold text-primary">{{ $infos['label'] }}</h2>
                <span class="text-xs font-mono text-muted bg-surface border border-border rounded-full px-2.5 py-0.5">{{ $demandes[$statut]->count() }}</span>
            </div>

            <div class="space-y-3">
                @foreach ($demandes[$statut] as $demande)
                <div class="bg-surface border border-border rounded-xl p-5 hover:bg-white dark:hover:bg-white/[0.07] hover:border-accent/30 transition-all duration-300">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4 flex-1 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-primary/5 border border-border flex items-center justify-center text-primary font-bold shrink-0">
                                {{ strtoupper(substr($demande->membre->prenom ?? '?', 0, 1)) }}{{ strtoupper(substr($demande->membre->nom ?? '', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-primary text-sm">{{ $demande->membre->full_name }}</p>
                                <div class="flex items-center gap-2 mt-1.5">
                                    <span class="px-2.5 py-0.5 text-[11px] font-mono rounded-full bg-muted/10 text-muted border border-border">{{ $demande->from_level }}</span>
                                    <i class="fas fa-arrow-right text-[10px] text-muted"></i>
                                    <span class="px-2.5 py-0.5 text-[11px] font-mono rounded-full bg-accent/10 text-accent border border-accent/30">{{ $demande->to_level }}</span>
                                    @if ($statut === 'approuvee')
                                        <span class="text-success text-xs ml-2"><i class="fas fa-check-circle mr-1"></i>Traitée</span>
                                    @endif
                                </div>
                                <p class="text-xs text-muted mt-1.5"><i class="far fa-clock mr-1"></i>{{ $demande->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        @if ($statut === 'en_attente')
                        <div class="flex items-center gap-2 shrink-0">
                            <form method="POST" action="{{ route('admin.demandes.approuver', $demande) }}">
                                @csrf @method('PUT')
                                <button type="submit" class="px-4 py-2 bg-success text-white text-xs font-bold rounded-lg hover:bg-success/90 transition-all hover:scale-105">
                                    <i class="fas fa-check mr-1"></i>Approuver
                                </button>
                            </form>
                            <button onclick="openRefusModal({{ $demande->id }})" class="px-4 py-2 bg-danger text-white text-xs font-bold rounded-lg hover:bg-danger/90 transition-all hover:scale-105">
                                <i class="fas fa-times mr-1"></i>Refuser
                            </button>
                        </div>
                        @endif
                    </div>

                    @if ($statut === 'refusee' && $demande->motif_refus)
                    <div class="mt-4 p-4 bg-danger/5 border border-danger/20 rounded-xl">
                        <p class="text-xs font-bold text-danger uppercase tracking-wider mb-1">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Motif du refus
                        </p>
                        <p class="text-sm text-danger/90">{{ $demande->motif_refus }}</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @endforeach

    @if ($demandes->flatten()->count() === 0)
    <div class="text-center py-20">
        <div class="w-20 h-20 mx-auto rounded-full bg-surface border border-border flex items-center justify-center mb-6">
            <i class="fas fa-inbox text-3xl text-muted"></i>
        </div>
        <h3 class="text-lg font-bold text-primary mb-2">Aucune demande</h3>
        <p class="text-sm text-muted">Les demandes de progression des membres apparaîtront ici.</p>
    </div>
    @endif
</div>

<form id="refusForm" method="POST" class="hidden">
    @csrf @method('PUT')
    <div id="refusModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div class="bg-surface border border-border rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl animate-fade-in-up">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-primary flex items-center gap-2">
                    <i class="fas fa-times-circle text-danger"></i> Motif du refus
                </h3>
                <button type="button" onclick="closeModal('refusModal')" class="w-8 h-8 rounded-lg bg-surface text-muted hover:text-text hover:bg-white/10 transition-colors flex items-center justify-center">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <p class="text-sm text-muted mb-4">Veuillez expliquer la raison du refus à ce membre.</p>
            <textarea name="motif_refus" rows="4" required class="w-full px-4 py-3 border border-border rounded-xl bg-bg text-text focus:outline-none focus:border-danger focus:ring-1 focus:ring-danger/30 transition-colors text-sm" placeholder="Expliquez le motif du refus..."></textarea>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeModal('refusModal')" class="px-5 py-2.5 text-sm font-bold text-muted hover:text-text hover:bg-surface border border-border rounded-lg transition-colors">Annuler</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold bg-danger text-white rounded-lg hover:bg-danger/90 transition-colors">
                    <i class="fas fa-check mr-1"></i>Confirmer le refus
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    function openRefusModal(id) {
        document.getElementById('refusForm').action = '{{ url("admin/demandes-progression") }}/' + id + '/refuser';
        document.getElementById('refusModal').classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
</script>
@endpush