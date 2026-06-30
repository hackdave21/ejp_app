@extends('layouts.admin')

@section('title', 'PV Réunions')
@section('page_title', '<i class="fas fa-file-alt text-accent"></i> PV des Réunions')

@section('content')
<div class="flex justify-end">
    <button id="add-pv-btn" class="bg-accent text-primary font-bold px-6 py-2.5 rounded-xl hover:scale-105 transition-transform flex items-center gap-2">
        <i class="fas fa-plus"></i> Nouveau PV
    </button>
</div>

@if (session('success'))
    <div class="p-4 bg-success/10 text-success rounded-2xl border border-success/20 text-sm font-bold">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @forelse ($reunions as $reunion)
    @php
        $typeColors = [
            'coordination' => 'bg-blue-50 text-primary',
            'urgence' => 'bg-amber-50 text-accent',
            'generale' => 'bg-purple-50 text-purple-600',
        ];
        $typeColor = $typeColors[$reunion->type] ?? 'bg-blue-50 text-primary';
        $isArchived = $reunion->statut === 'archive';
        $participants = is_array($reunion->participants) ? $reunion->participants : (json_decode($reunion->participants, true) ?? []);
        $participantCount = count($participants);
    @endphp
    <div class="pv-card bg-surface p-8 rounded-3xl animate-fade-in-up">
        <div class="flex items-center justify-between mb-6">
            <span class="px-3 py-1 {{ $typeColor }} text-[10px] font-bold rounded-full uppercase tracking-widest">{{ $reunion->type }}</span>
            @if ($isArchived)
            <span class="text-gray-400 text-[10px] font-bold uppercase flex items-center gap-1">
                <i class="fas fa-archive"></i> Archivé
            </span>
            @else
            <span class="text-success text-[10px] font-bold uppercase flex items-center gap-1">
                <i class="fas fa-check-circle"></i> Actif
            </span>
            @endif
        </div>
        <h3 class="text-xl font-serif font-bold text-primary mb-2">{{ $reunion->titre }}</h3>
        <p class="text-xs text-muted mb-6 italic">{{ $reunion->date->translatedFormat('j F Y') }}{{ $reunion->created_at ? ' • ' . substr($reunion->created_at->format('H:i'), 0, 5) : '' }}</p>

        @if ($participantCount > 0)
        <div class="flex items-center gap-2 mb-8">
            <div class="flex -space-x-3">
                @foreach (array_slice($participants, 0, 3) as $p)
                <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-100 text-primary flex items-center justify-center font-bold text-[10px]">{{ strtoupper(substr($p, 0, 2)) }}</div>
                @endforeach
                @if ($participantCount > 3)
                <div class="w-8 h-8 rounded-full border-2 border-white bg-surface text-muted flex items-center justify-center font-bold text-[10px]">+{{ $participantCount - 3 }}</div>
                @endif
            </div>
            <span class="text-[10px] text-muted font-bold uppercase tracking-tighter">Participants</span>
        </div>
        @endif

        <div class="flex gap-2">
            <a href="{{ route('admin.reunions.show', $reunion) }}" class="flex-1 py-2.5 bg-surface text-muted font-bold text-xs rounded-xl hover:bg-primary hover:text-primary-text transition-all text-center">Consulter</a>
            @if (!$isArchived)
            <form method="POST" action="{{ route('admin.reunions.archiver', $reunion) }}" class="w-11 h-11">
                @csrf @method('PUT')
                <button type="submit" class="w-full h-full flex items-center justify-center border border-border rounded-xl text-muted hover:text-accent transition-colors">
                    <i class="fas fa-archive"></i>
                </button>
            </form>
            @endif
            <form method="POST" action="{{ route('admin.reunions.destroy', $reunion) }}" onsubmit="return confirm('Supprimer ce PV ?')" class="w-11 h-11">
                @csrf @method('DELETE')
                <button type="submit" class="w-full h-full flex items-center justify-center border border-border rounded-xl text-muted hover:text-danger transition-colors">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-20 text-muted">
        <i class="fas fa-file-alt text-5xl mb-4"></i>
        <p class="text-lg font-bold">Aucun procès-verbal</p>
        <p class="text-sm">Créez votre premier PV de réunion.</p>
    </div>
    @endforelse
</div>

<div id="add-drawer" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-dark/60 backdrop-blur-sm" onclick="document.getElementById('add-drawer').classList.add('hidden')"></div>
    <div class="absolute right-0 top-0 bottom-0 w-full max-w-2xl bg-surface shadow-2xl animate-slide-in-right flex flex-col">
        <header class="p-10 border-b border-border flex items-center justify-between shrink-0">
            <h3 class="text-2xl font-bold text-primary font-serif">Nouveau Procès Verbal</h3>
            <button onclick="document.getElementById('add-drawer').classList.add('hidden')" class="w-10 h-10 rounded-full hover:bg-surface text-muted transition-colors"><i class="fas fa-times text-xl"></i></button>
        </header>

        <form method="POST" action="{{ route('admin.reunions.store') }}" class="flex-1 flex flex-col">
            @csrf
            <div class="flex-1 overflow-y-auto p-10 custom-scrollbar space-y-8">
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Titre</label>
                        <input type="text" name="titre" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-1 focus:ring-accent" placeholder="Ex: Coordination Hebdo">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Type</label>
                        <select name="type" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm text-muted outline-none">
                            <option value="generale">Générale</option>
                            <option value="coordination">Coordination</option>
                            <option value="urgence">Urgence</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Date</label>
                    <input type="date" name="date" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-1 focus:ring-accent">
                </div>

                <div class="space-y-4">
                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Contenu du PV</label>
                    <textarea name="contenu" required class="w-full bg-bg border-none rounded-2xl py-4 px-6 text-sm text-primary min-h-[250px] focus:ring-1 focus:ring-accent" placeholder="Détaillez les points discutés, les décisions prises..."></textarea>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Participants (JSON)</label>
                    <input type="text" name="participants" class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-1 focus:ring-accent" placeholder='["Chef Amos", "Marie Koné"]'>
                </div>

                <div class="space-y-4">
                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Sujets de Prière</label>
                    <textarea name="sujets_priere" class="w-full bg-bg border-none rounded-2xl py-4 px-6 text-sm text-primary min-h-[120px] focus:ring-1 focus:ring-accent" placeholder="Sujets de prière abordés..."></textarea>
                </div>
            </div>

            <footer class="p-10 border-t border-border shrink-0">
                <button type="submit" class="w-full py-4 bg-primary text-primary-text font-bold rounded-2xl shadow-lg shadow-primary/20">Enregistrer et Archiver</button>
            </footer>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('add-pv-btn').onclick = () => {
        document.getElementById('add-drawer').classList.remove('hidden');
    };
</script>
@endpush
