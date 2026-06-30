@extends('layouts.chef')
@section('title', 'Tableau de Bord Chef')
@section('greeting', 'Tableau de Bord')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-surface p-6 rounded-3xl border border-border shadow-sm flex items-center gap-4 animate-fade-in-up" style="animation-delay: 0.1s">
        <div class="w-14 h-14 kpi-icon-blue rounded-2xl flex items-center justify-center text-xl">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <p class="text-[10px] text-muted font-bold uppercase tracking-widest">Disciples</p>
            <div class="flex items-baseline gap-2">
                <p class="text-3xl font-bold text-primary font-serif">{{ $stats['total_membres'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-surface p-6 rounded-3xl border border-border shadow-sm flex items-center gap-4 animate-fade-in-up" style="animation-delay: 0.2s">
        <div class="w-14 h-14 kpi-icon-green rounded-2xl flex items-center justify-center text-xl">
            <i class="fas fa-file-alt"></i>
        </div>
        <div>
            <p class="text-[10px] text-muted font-bold uppercase tracking-widest">PV Réunions</p>
            <div class="flex items-baseline gap-2">
                <p class="text-3xl font-bold text-primary font-serif">{{ $stats['total_reunions'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-surface p-6 rounded-3xl border border-border shadow-sm flex items-center justify-between animate-fade-in-up" style="animation-delay: 0.3s">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 kpi-icon-red rounded-2xl flex items-center justify-center text-xl">
                <i class="fas fa-file-signature"></i>
            </div>
            <div>
                <p class="text-[10px] text-muted font-bold uppercase tracking-widest">À valider</p>
                <p class="text-3xl font-bold text-primary font-serif">{{ $stats['demandes_attente'] }}</p>
            </div>
        </div>
        <a href="{{ route('chef.demandes.index') }}" class="px-4 py-2 bg-surface text-muted font-bold text-xs rounded-xl hover:bg-chef hover:text-white transition-all">Gérer</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    <section class="bg-surface rounded-3xl border border-border shadow-sm p-6 animate-fade-in-up" style="animation-delay: 0.4s">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-primary font-serif"><i class="fas fa-exclamation-triangle text-amber-500"></i> Nécessitent votre attention</h3>
            <span class="text-xs text-muted font-medium">Basé sur l'assiduité</span>
        </div>

        <div class="space-y-4">
            @forelse ($membres->take(5) as $membre)
            <div class="flex items-center justify-between p-4 alert-amber rounded-2xl border">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-surface text-amber-500 flex items-center justify-center font-bold text-xs shadow-sm">
                        {{ strtoupper(substr($membre->prenom, 0, 1)) }}{{ strtoupper(substr($membre->nom, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold text-sm text-primary">{{ $membre->full_name }}</p>
                        <p class="text-xs text-amber-500">{{ $membre->email ?? 'Membre' }}</p>
                    </div>
                </div>
                <a href="{{ route('chef.membres.show', $membre) }}" class="w-8 h-8 rounded-full bg-surface text-muted hover:text-primary transition-colors shadow-sm flex items-center justify-center">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            @empty
            <p class="text-sm text-muted">Aucun membre nécessitant attention.</p>
            @endforelse
        </div>
    </section>

    <section class="bg-surface rounded-3xl border border-border shadow-sm p-6 animate-fade-in-up" style="animation-delay: 0.5s">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-primary font-serif">Activité de la Famille</h3>
            <a href="{{ route('chef.demandes.index') }}" class="text-xs text-accent font-bold hover:underline">Voir tout</a>
        </div>

        <div class="relative border-l-2 border-border ml-3 space-y-6">
            @forelse ($demandes as $demande)
            <div class="relative pl-6">
                <div class="timeline-dot absolute -left-[9px] top-1 w-4 h-4 bg-success rounded-full border-4 border-white shadow-sm"></div>
                <p class="text-xs text-muted mb-1">{{ $demande->created_at->format('d M Y, H:i') }}</p>
                <p class="text-sm font-medium text-primary">
                    <span class="font-bold">{{ $demande->membre->full_name }}</span> a soumis une demande <span class="font-bold">{{ $demande->from_level }}</span> → <span class="font-bold">{{ $demande->to_level }}</span>
                </p>
            </div>
            @empty
            <div class="relative pl-6">
                <p class="text-sm text-muted">Aucune activité récente.</p>
            </div>
            @endforelse
        </div>
    </section>

</div>

<section class="bg-surface rounded-3xl border border-border shadow-sm p-6 animate-fade-in-up" style="animation-delay: 0.6s">
    <div class="flex items-center justify-between mb-6">
        <h3 class="font-bold text-primary font-serif"><i class="fas fa-trophy text-accent"></i> Classement de la Famille</h3>
        <span class="text-xs text-muted font-medium">Top membres</span>
    </div>

    <div class="space-y-3">
        @forelse ($membres->take(10) as $index => $membre)
        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-surface transition-colors {{ $index < 3 ? 'border border-border' : '' }}">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full {{ $index == 0 ? 'bg-yellow-400 text-white' : ($index == 1 ? 'bg-gray-300 text-white' : ($index == 2 ? 'bg-amber-600 text-white' : 'bg-surface border border-border text-muted')) }} flex items-center justify-center font-bold text-xs">
                    {{ $index + 1 }}
                </div>
                <div class="w-10 h-10 rounded-full bg-primary text-primary-text flex items-center justify-center font-bold text-xs">
                    {{ strtoupper(substr($membre->prenom, 0, 1)) }}{{ strtoupper(substr($membre->nom, 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-sm text-primary">{{ $membre->full_name }}</p>
                    <p class="text-xs text-muted">{{ $membre->chefResponsable?->user?->full_name ?? '—' }}</p>
                </div>
            </div>
            <span class="text-xs font-bold text-chef">{{ $loop->remaining == 0 ? 'Dernier' : 'Membre' }}</span>
        </div>
        @empty
        <p class="text-sm text-muted">Aucun membre à afficher.</p>
        @endforelse
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Dashboard scripts
</script>
@endpush
