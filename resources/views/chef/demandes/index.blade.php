@extends('layouts.chef')

@section('title', 'Demandes de Progression | EJP Chef')

@section('greeting')
    <i class="fas fa-clipboard-list text-accent"></i> Demandes de Progression
@endsection

@section('content')
    <style>
        .request-card { transition: all 0.3s ease; border: 1px solid #115E59; }
        .request-card:hover { transform: translateY(-3px); border-color: #27AE60; box-shadow: 0 12px 30px -10px rgba(17,94,89,0.25); }
        .dark .stat-warning { background-color: rgba(245, 166, 35, 0.12) !important; border-color: rgba(245, 166, 35, 0.35) !important; }
        .dark .stat-warning .stat-label { color: #9CA3AF !important; }
        .dark .stat-warning .stat-value { color: #F9FAFB !important; }
        .dark .level-badge { background-color: rgba(255,255,255,0.07) !important; border-color: rgba(255,255,255,0.15) !important; }
        .animate-slide-out-right { animation: slideOutRight 0.5s ease-in forwards; }
        @keyframes slideOutRight { 0% { transform: translateX(0); opacity: 1; } 100% { transform: translateX(100%); opacity: 0; } }
    </style>

    @if (session('success'))
        <div class="mb-6 p-4 bg-success/10 text-success font-bold rounded-2xl border border-success/20 animate-fade-in-up">{{ session('success') }}</div>
    @endif

    @php
        $enAttente = $demandes['en_attente'] ?? collect();
        $approuvees = $demandes['approuvee'] ?? collect();
        $refusees = $demandes['refusee'] ?? collect();
        $traitees = $approuvees->merge($refusees);
        $totalAttente = $enAttente->count();

        function levelIcon($l) {
            return match($l) {
                'star' => 'fa-star',
                'pilote' => 'fa-plane',
                'pilier' => 'fa-university',
                'missionnaire' => 'fa-globe',
                default => 'fa-seedling',
            };
        }
        function levelColor($l) {
            return match($l) {
                'star' => 'text-amber-500',
                'pilote' => 'text-blue-500',
                'pilier' => 'text-purple-500',
                'missionnaire' => 'text-emerald-500',
                default => 'text-muted',
            };
        }
        function formatLevel($l) {
            return ucfirst(str_replace('_', ' ', $l));
        }
    @endphp

    <div class="flex items-center justify-between mb-6">
        <div>
            <span class="px-2 py-1 {{ $totalAttente > 0 ? 'bg-danger/10 text-danger' : 'bg-success/10 text-success' }} text-xs font-bold rounded-lg" id="header-badge">{{ $totalAttente }} en attente</span>
        </div>
        <div class="bg-surface p-1 rounded-xl flex text-sm font-bold text-muted border border-border">
            <button id="tab-attente" class="px-4 py-1.5 bg-surface text-primary rounded-lg shadow-sm" onclick="switchTab('attente')">En attente ({{ $totalAttente }})</button>
            <button id="tab-traitees" class="px-4 py-1.5 hover:text-primary transition-colors" onclick="switchTab('traitees')">Traitées</button>
        </div>
    </div>

    <div id="requests-container" class="space-y-6">
        @forelse ($enAttente as $demande)
            <div class="request-card bg-surface p-6 rounded-3xl animate-fade-in-up attente" id="req-{{ $demande->id }}">
                <div class="flex items-start justify-between">
                    <div class="flex gap-6 w-full">
                        <div class="w-14 h-14 rounded-full bg-accent text-white flex items-center justify-center font-bold text-lg shrink-0 shadow-md">
                            {{ strtoupper(substr($demande->membre->prenom, 0, 1)) }}{{ strtoupper(substr($demande->membre->nom, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h3 class="text-lg font-bold text-primary">{{ $demande->membre->full_name }}</h3>
                                    <p class="text-xs text-muted">Demande soumise {{ $demande->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="level-badge flex items-center gap-3 bg-bg px-4 py-2 rounded-xl border border-border">
                                    <span class="flex items-center gap-1 text-xs font-bold {{ levelColor($demande->from_level) }}"><i class="fas {{ levelIcon($demande->from_level) }}"></i> {{ formatLevel($demande->from_level) }}</span>
                                    <i class="fas fa-arrow-right text-gray-400 dark:text-gray-500"></i>
                                    <span class="flex items-center gap-1 text-xs font-bold {{ levelColor($demande->to_level) }}"><i class="fas {{ levelIcon($demande->to_level) }}"></i> {{ formatLevel($demande->to_level) }}</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4 mt-6">
                                @php $formationsOk = !is_null($demande->formations_score) && $demande->formations_score >= 80; @endphp
                                <div class="p-3 rounded-xl border {{ $formationsOk ? 'border-success/20 bg-success/5' : 'border-danger/20 bg-danger/5' }} flex items-start gap-3">
                                    <i class="fas {{ $formationsOk ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }} mt-0.5"></i>
                                    <div>
                                        <p class="text-[10px] font-bold text-muted uppercase tracking-widest">Formations</p>
                                        <p class="text-sm font-bold {{ $formationsOk ? 'text-primary' : 'text-danger' }}">{{ $demande->formations_score ?? '—' }}<span class="font-normal text-xs text-muted"> {{ !is_null($demande->formations_score) ? '%' : '' }}</span></p>
                                    </div>
                                </div>
                                @php $assiduiteOk = !is_null($demande->assiduite_score) && $demande->assiduite_score >= 80; @endphp
                                <div class="p-3 rounded-xl border {{ $assiduiteOk ? 'border-success/20 bg-success/5' : 'border-danger/20 bg-danger/5' }} flex items-start gap-3">
                                    <i class="fas {{ $assiduiteOk ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }} mt-0.5"></i>
                                    <div>
                                        <p class="text-[10px] font-bold text-muted uppercase tracking-widest">Assiduité</p>
                                        <p class="text-sm font-bold {{ $assiduiteOk ? 'text-primary' : 'text-danger' }}">{{ $demande->assiduite_score ?? '—' }}<span class="font-normal text-xs text-muted"> {{ !is_null($demande->assiduite_score) ? '%' : '' }}</span></p>
                                    </div>
                                </div>
                                @php $ancienneteOk = !is_null($demande->anciennete) && $demande->anciennete >= 6; @endphp
                                <div class="p-3 rounded-xl border {{ $ancienneteOk ? 'border-success/20 bg-success/5' : 'border-danger/20 bg-danger/5' }} flex items-start gap-3">
                                    <i class="fas {{ $ancienneteOk ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }} mt-0.5"></i>
                                    <div>
                                        <p class="text-[10px] font-bold text-muted uppercase tracking-widest">Ancienneté</p>
                                        <p class="text-sm font-bold {{ $ancienneteOk ? 'text-primary' : 'text-danger' }}">{{ $demande->anciennete ?? '—' }}<span class="font-normal text-xs text-muted"> {{ !is_null($demande->anciennete) ? 'Mois' : '' }}</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t border-border flex justify-end gap-3">
                    <button onclick="openRejectModal({{ $demande->id }}, '{{ $demande->membre->full_name }}')" class="px-6 py-2.5 bg-surface border border-danger/20 text-danger hover:bg-danger/5 font-bold rounded-xl transition-colors text-sm">Refuser</button>
                    <form method="POST" action="{{ route('chef.demandes.approuver', $demande) }}" class="inline" onsubmit="setTimeout(() => approveRequest('req-{{ $demande->id }}'), 100)">
                        @csrf @method('PUT')
                        <button type="submit" class="px-6 py-2.5 bg-success hover:bg-[#219653] text-white font-bold rounded-xl transition-colors text-sm shadow-md shadow-success/20 flex items-center gap-2">
                            <i class="fas fa-check"></i> Valider la progression
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center h-64 text-muted animate-fade-in-up" id="empty-attente">
                <i class="fas fa-check-circle text-5xl mb-4 text-success/50"></i>
                <p class="text-lg font-medium">Toutes les demandes ont été traitées.</p>
                <p class="text-sm mt-1">Excellent travail, Chef !</p>
            </div>
        @endforelse

        @foreach ($traitees as $demande)
            <div class="request-card bg-surface p-6 rounded-3xl animate-fade-in-up traitee" id="req-{{ $demande->id }}">
                <div class="flex items-start justify-between">
                    <div class="flex gap-6 w-full">
                        <div class="w-14 h-14 rounded-full bg-gray-300 text-white flex items-center justify-center font-bold text-lg shrink-0 shadow-md">
                            {{ strtoupper(substr($demande->membre->prenom, 0, 1)) }}{{ strtoupper(substr($demande->membre->nom, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h3 class="text-lg font-bold text-primary">{{ $demande->membre->full_name }}</h3>
                                    <p class="text-xs text-muted">Demande soumise {{ $demande->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if ($demande->statut === 'approuvee')
                                        <span class="px-3 py-1 bg-success/10 text-success text-xs font-bold rounded-full"><i class="fas fa-check-circle"></i> Approuvée</span>
                                    @else
                                        <span class="px-3 py-1 bg-danger/10 text-danger text-xs font-bold rounded-full"><i class="fas fa-times-circle"></i> Refusée</span>
                                    @endif
                                    <div class="level-badge flex items-center gap-3 bg-bg px-4 py-2 rounded-xl border border-border">
                                        <span class="flex items-center gap-1 text-xs font-bold {{ levelColor($demande->from_level) }}"><i class="fas {{ levelIcon($demande->from_level) }}"></i> {{ formatLevel($demande->from_level) }}</span>
                                        <i class="fas fa-arrow-right text-gray-400"></i>
                                        <span class="flex items-center gap-1 text-xs font-bold {{ levelColor($demande->to_level) }}"><i class="fas {{ levelIcon($demande->to_level) }}"></i> {{ formatLevel($demande->to_level) }}</span>
                                    </div>
                                </div>
                            </div>
                            @if ($demande->statut === 'refusee' && $demande->motif_refus)
                                <div class="mt-4 p-4 bg-danger/5 border border-danger/20 rounded-2xl text-sm text-danger">{{ $demande->motif_refus }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div id="reject-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-dark/60 backdrop-blur-sm" onclick="closeRejectModal()"></div>
        <div class="relative w-full max-w-lg bg-surface rounded-3xl p-8 shadow-2xl animate-fade-in-up">
            <h3 class="text-xl font-bold text-primary font-serif mb-2">Refuser la demande</h3>
            <p class="text-sm text-muted mb-6">Fournissez un motif à <span id="reject-name" class="font-bold"></span> pour l'aider à comprendre ce qui manque pour progresser.</p>
            <form id="reject-form" method="POST">
                @csrf @method('PUT')
                <textarea name="motif_refus" id="reject-reason" class="w-full bg-bg border border-border rounded-2xl py-4 px-6 text-sm text-primary min-h-[120px] focus:ring-1 focus:ring-chef outline-none mb-6" placeholder="Ex: Il te manque encore 2 modules de formation à valider..." required></textarea>
                <div class="flex gap-4">
                    <button type="button" onclick="closeRejectModal()" class="flex-1 py-3 bg-surface border border-border text-muted font-bold rounded-xl hover:bg-surface transition-colors">Annuler</button>
                    <button type="submit" class="flex-1 py-3 bg-danger text-white font-bold rounded-xl hover:bg-red-700 transition-colors shadow-md shadow-danger/20">Confirmer le refus</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let currentCount = {{ $totalAttente }};

    function switchTab(tab) {
        document.getElementById('tab-attente').className = 'px-4 py-1.5 hover:text-primary transition-colors';
        document.getElementById('tab-traitees').className = 'px-4 py-1.5 hover:text-primary transition-colors';
        document.querySelectorAll('.request-card').forEach(c => c.classList.remove('hidden'));
        if (tab === 'attente') {
            document.getElementById('tab-attente').className = 'px-4 py-1.5 bg-surface text-primary rounded-lg shadow-sm';
            document.querySelectorAll('.request-card.traitee').forEach(c => c.classList.add('hidden'));
        } else {
            document.getElementById('tab-traitees').className = 'px-4 py-1.5 bg-surface text-primary rounded-lg shadow-sm';
            document.querySelectorAll('.request-card.attente').forEach(c => c.classList.add('hidden'));
        }
    }

    function approveRequest(id) {
        const card = document.getElementById(id);
        if (!card) return;
        card.style.borderColor = '#27AE60';
        card.style.backgroundColor = 'rgba(39, 174, 96, 0.05)';
        setTimeout(() => {
            card.classList.add('animate-slide-out-right');
            setTimeout(() => {
                card.classList.add('hidden');
                updateCounters();
            }, 500);
        }, 300);
    }

    function openRejectModal(id, name) {
        document.getElementById('reject-name').innerText = name;
        document.getElementById('reject-reason').value = '';
        document.getElementById('reject-form').action = '/chef/demandes-progression/' + id + '/refuser';
        document.getElementById('reject-modal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('reject-modal').classList.add('hidden');
    }

    function updateCounters() {
        currentCount--;
        const badge = document.getElementById('header-badge');
        const tabBtn = document.getElementById('tab-attente');
        if (currentCount > 0) {
            badge.innerText = currentCount + ' en attente';
            tabBtn.innerHTML = 'En attente (' + currentCount + ')';
        } else {
            badge.innerText = '0 en attente';
            badge.className = 'px-2 py-1 bg-success/10 text-success text-xs font-bold rounded-lg';
            tabBtn.innerHTML = 'En attente (0)';
            const sb = document.getElementById('sidebar-badge');
            if (sb) sb.classList.add('hidden');
        }
    }

    @if ($totalAttente === 0)
        document.addEventListener('DOMContentLoaded', function() {
            const sb = document.getElementById('sidebar-badge');
            if (sb) sb.classList.add('hidden');
        });
    @endif
</script>
@endpush
