@extends('layouts.chef')

@section('title', 'PV Réunions | EJP Chef')

@section('greeting')
    <i class="fas fa-file-alt text-accent"></i> PV Réunions Famille
@endsection

@section('content')
    <style>
        .pv-card { transition: all 0.3s ease; border: 1px solid #115E59; }
        .pv-card:hover { transform: translateY(-5px); border-color: #27AE60; box-shadow: 0 12px 30px -10px rgba(17,94,89,0.25); }
        .avatar-blue { background-color: rgba(59,130,246,0.18); color: #3B82F6; }
        .avatar-pink { background-color: rgba(236,72,153,0.18); color: #EC4899; }
    </style>

    @if (session('success'))
        <div class="mb-6 p-4 bg-success/10 text-success font-bold rounded-2xl border border-success/20 animate-fade-in-up">{{ session('success') }}</div>
    @endif

    <div class="flex items-center justify-end mb-8">
        <button id="add-pv-btn" class="bg-chef text-white font-bold px-6 py-2.5 rounded-xl hover:bg-[#0D4D49] transition-colors flex items-center gap-2 shadow-md shadow-chef/20">
            <i class="fas fa-plus"></i> Nouveau PV
        </button>
    </div>

    @if ($reunions->isEmpty())
        <div class="flex flex-col items-center justify-center h-64 text-muted animate-fade-in-up">
            <i class="fas fa-file-alt text-5xl mb-4 text-muted/50"></i>
            <p class="text-lg font-medium">Aucun procès-verbal pour le moment.</p>
            <p class="text-sm mt-1">Créez votre premier PV de réunion.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($reunions as $reunion)
            <div class="pv-card bg-surface p-8 rounded-3xl animate-fade-in-up">
                <div class="flex items-center justify-between mb-6">
                    @if ($reunion->statut === 'archive' || $reunion->statut === 'soumis')
                        <span class="px-3 py-1 bg-chef/10 text-chef text-[10px] font-bold rounded-full uppercase tracking-widest">SOUMIS</span>
                    @else
                        <span class="px-3 py-1 bg-surface text-muted text-[10px] font-bold rounded-full uppercase tracking-widest border border-border">BROUILLON</span>
                    @endif
                    <span class="text-muted text-xs font-medium"><i class="fas fa-calendar-alt"></i> {{ $reunion->date->format('d M Y') }}</span>
                </div>
                <h3 class="text-xl font-serif font-bold text-primary mb-2">{{ $reunion->titre }}</h3>
                @if ($reunion->contenu)
                    <p class="text-xs text-muted mb-6 line-clamp-2">{{ Str::limit($reunion->contenu, 100) }}</p>
                @else
                    <p class="text-xs text-muted mb-6 italic">PV en cours de rédaction...</p>
                @endif

                <div class="flex items-center justify-between mb-8 pb-6 border-b border-border">
                    <div>
                        <p class="text-[10px] text-muted font-bold uppercase tracking-widest mb-1">Présences</p>
                        @php
                            $participants = is_array($reunion->participants) ? $reunion->participants : (json_decode($reunion->participants, true) ?? []);
                            $count = count($participants);
                        @endphp
                        @if ($count > 0)
                            <p class="text-sm font-bold text-primary">{{ $count }} <span class="text-xs font-normal text-muted">membres</span></p>
                        @else
                            <p class="text-sm font-bold text-muted">—</p>
                        @endif
                    </div>
                    @if ($count > 0)
                    <div class="flex -space-x-3">
                        @foreach (array_slice($participants, 0, 2) as $p)
                            @php
                                $pInitials = strtoupper(substr($p['prenom'] ?? $p, 0, 1) . substr($p['nom'] ?? '', 0, 1));
                            @endphp
                            <div class="w-8 h-8 rounded-full border-2 border-surface avatar-blue flex items-center justify-center font-bold text-[10px]">{{ $pInitials }}</div>
                        @endforeach
                        @if ($count > 2)
                            <div class="w-8 h-8 rounded-full border-2 border-surface bg-surface text-muted flex items-center justify-center font-bold text-[10px]">+{{ $count - 2 }}</div>
                        @endif
                    </div>
                    @endif
                </div>

                <div class="flex gap-2">
                    @if ($reunion->statut === 'brouillon' || $reunion->statut === 'draft')
                        <button onclick="editReunion({{ $reunion->id }})" class="flex-1 py-2.5 bg-chef text-white font-bold text-xs rounded-xl hover:bg-[#0D4D49] transition-all">Continuer la saisie</button>
                        <form method="POST" action="{{ route('chef.reunions.destroy', $reunion) }}" onsubmit="return confirm('Supprimer ce PV ?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-11 h-11 flex items-center justify-center border border-border rounded-xl text-muted hover:text-danger transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('chef.reunions.show', $reunion) }}" class="flex-1 py-2.5 bg-bg text-primary font-bold text-xs rounded-xl hover:bg-primary hover:text-primary-text transition-all text-center">Consulter</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif

    <div id="add-drawer" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-dark/60 backdrop-blur-sm" onclick="document.getElementById('add-drawer').classList.add('hidden')"></div>
        <div class="absolute right-0 top-0 bottom-0 w-full max-w-2xl bg-surface shadow-2xl animate-fade-in-up flex flex-col">
            <header class="p-10 border-b border-border flex items-center justify-between shrink-0">
                <h3 class="text-2xl font-bold text-primary font-serif">Nouveau PV de Réunion</h3>
                <button onclick="document.getElementById('add-drawer').classList.add('hidden')" class="w-10 h-10 rounded-full hover:bg-surface text-muted transition-colors"><i class="fas fa-times text-xl"></i></button>
            </header>
            <form method="POST" action="{{ route('chef.reunions.store') }}" class="flex flex-col flex-1">
                @csrf
                <div class="flex-1 overflow-y-auto p-10 custom-scrollbar space-y-8">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Titre de la réunion</label>
                            <input type="text" name="titre" required class="w-full bg-bg border border-border rounded-xl py-3 px-4 text-sm focus:ring-1 focus:ring-chef outline-none" placeholder="Ex: Réunion Mensuelle Famille">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Date</label>
                            <input type="date" name="date" required class="w-full bg-bg border border-border rounded-xl py-3 px-4 text-sm text-muted focus:ring-1 focus:ring-chef outline-none">
                        </div>
                    </div>
                    <div class="space-y-4">
                        <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Points Abordés</label>
                        <textarea name="contenu" required class="w-full bg-bg border border-border rounded-2xl py-4 px-6 text-sm text-primary min-h-[150px] focus:ring-1 focus:ring-chef outline-none" placeholder="Sujets discutés, enseignements partagés..."></textarea>
                    </div>
                    <div class="space-y-4">
                        <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Sujets de Prière / Suivi</label>
                        <textarea name="sujets_priere" class="w-full bg-bg border border-border rounded-2xl py-4 px-6 text-sm text-primary min-h-[100px] focus:ring-1 focus:ring-chef outline-none" placeholder="Sujets nécessitant un suivi particulier..."></textarea>
                    </div>
                </div>
                <footer class="p-10 border-t border-border shrink-0 flex gap-4">
                    <button type="button" onclick="document.getElementById('add-drawer').classList.add('hidden')" class="w-1/3 py-4 bg-surface border border-border text-muted font-bold rounded-2xl hover:bg-surface transition-colors">Annuler</button>
                    <button type="submit" class="flex-1 py-4 bg-chef text-white font-bold rounded-2xl hover:bg-[#0D4D49] transition-colors shadow-lg shadow-chef/20 flex items-center justify-center gap-2">
                        <i class="fas fa-check"></i> Créer le PV
                    </button>
                </footer>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('add-pv-btn')?.addEventListener('click', function() {
        document.getElementById('add-drawer').classList.remove('hidden');
    });

    function editReunion(id) {
        window.location.href = '/chef/reunions/' + id;
    }
</script>
@endpush
