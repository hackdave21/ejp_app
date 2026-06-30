@extends('layouts.frontend')
@section('title', 'Ma Progression')
@section('page_title', 'Ma Progression')

@section('content')
@if (session('success'))
<div class="mb-6 p-4 bg-success/10 text-success font-bold rounded-2xl border border-success/20 flex items-center gap-3">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif
@if (session('error'))
<div class="mb-6 p-4 bg-danger/10 text-danger font-bold rounded-2xl border border-danger/20 flex items-center gap-3">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

<section class="bg-white dark:bg-black border border-border dark:border-white/10 rounded-3xl p-8 text-text relative overflow-hidden shadow-sm dark:shadow-none animate-slide-in-up">
    <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
        <div class="flex-1">
            <h1 class="text-3xl font-serif mb-4 text-primary">Votre voyage spirituel</h1>
            <p class="text-muted">"Celui qui a commencé en vous cette bonne œuvre la rendra parfaite." — Philippiens 1:6</p>
        </div>
        <div class="hidden md:block">
            <div class="w-32 h-32 border-4 border-accent/20 rounded-full flex items-center justify-center">
                <i class="fas fa-map-signs text-5xl text-accent"></i>
            </div>
        </div>
    </div>
</section>

<div class="grid lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-8">
        <div class="bg-surface p-8 rounded-3xl shadow-sm border border-border">
            <h3 class="text-xl font-bold text-primary mb-8">Parcours visuel</h3>
            <div class="space-y-12">
                @foreach ($levels as $key => $level)
                @php
                    $isCompleted = $currentOrder > $level['order'];
                    $isCurrent = $currentOrder === $level['order'];
                    $isLocked = $currentOrder < $level['order'];
                @endphp
                <div class="timeline-item flex gap-6 relative animate-fade-in-up" style="animation-delay: {{ 0.1 * $loop->iteration }}s">
                    <div class="node {{ $isCompleted || $isCurrent ? 'bg-accent text-white' : 'bg-surface text-muted' }}">
                        @if ($isCurrent && $key === 'pilote')
                        <div class="pulse-effect"></div>
                        @endif
                        <i class="fas {{ $isCompleted ? 'fa-check' : ($isLocked ? 'fa-lock' : $level['icon']) }}"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-lg font-bold {{ $isLocked ? 'text-muted' : 'text-primary' }}">
                                <i class="fas {{ $isCompleted ? 'fa-check-circle text-success mr-2' : ($isCurrent ? ($level['icon'] . ' text-accent mr-2 animate-pulse-soft') : ($level['icon'] . ' text-muted mr-2')) }}"></i>
                                {{ $key === 'nouveau_membre' ? '' : '' }}Niveau {{ $level['order'] + 1 }} : {{ $level['label'] }}
                            </span>
                            @if ($isCompleted)
                            <span class="px-2 py-0.5 bg-success/10 text-success text-[10px] font-bold rounded">COMPLÉTÉ</span>
                            @elseif ($isCurrent)
                            <span class="px-2 py-0.5 bg-accent/20 text-accent text-[10px] font-bold rounded">ACTUEL</span>
                            @else
                            <span class="px-2 py-0.5 bg-surface text-muted text-[10px] font-bold rounded">VERROUILLÉ</span>
                            @endif
                        </div>
                        <p class="text-sm text-muted">
                            @if ($isCompleted) Validé
                            @elseif ($isCurrent) @if ($key === 'star') Depuis le {{ auth()->user()->updated_at->format('d/m/Y') }} @else Conditions à remplir ci-dessous @endif
                            @else Verrouillé
                            @endif
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-surface p-8 rounded-3xl shadow-sm border border-border">
            <h3 class="text-xl font-bold text-primary mb-6">Historique des statuts</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-muted text-xs uppercase tracking-wider border-b border-border">
                            <th class="pb-4 font-medium">Date</th>
                            <th class="pb-4 font-medium">Statut</th>
                            <th class="pb-4 font-medium">Validé par</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse ($demandes->where('statut', 'approuvee') as $demande)
                        <tr class="border-b border-border">
                            <td class="py-4">{{ $demande->created_at->format('d/m/Y') }}</td>
                            <td class="py-4"><span class="font-bold text-primary">{{ ucfirst(str_replace('_', ' ', $demande->to_level)) }} <i class="fas fa-star text-accent ml-1"></i></span></td>
                            <td class="py-4">Système</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-muted italic">Aucun historique de progression.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="space-y-8">
        <div class="bg-surface p-8 rounded-3xl shadow-sm border border-border">
            <h3 class="text-lg font-bold text-primary mb-6">Devenir Pilote <i class="fas fa-plane text-accent ml-1"></i></h3>
            <ul class="space-y-4 mb-8">
                <li class="flex items-center gap-3">
                    <div class="w-6 h-6 bg-success/10 text-success rounded-full flex items-center justify-center text-xs">
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="text-sm text-muted">Formation initiale complétée</span>
                </li>
                <li class="space-y-2">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-amber-500/10 text-amber-500 rounded-full flex items-center justify-center text-xs">
                            <i class="fas fa-sync fa-spin" style="animation-duration: 3s"></i>
                        </div>
                        <span class="text-sm text-muted">Sessions de service : <span class="font-bold">{{ $formations->where('vu', true)->count() }}/12</span></span>
                    </div>
                    <div class="ml-9 w-full h-1.5 bg-surface rounded-full overflow-hidden">
                        <div class="h-full bg-accent rounded-full" style="width: {{ min(100, ($formations->where('vu', true)->count() / 12) * 100) }}%"></div>
                    </div>
                </li>
                <li class="flex items-center gap-3 opacity-40">
                    <div class="w-6 h-6 bg-gray-200 text-muted rounded-full flex items-center justify-center text-xs">
                        <i class="fas fa-times"></i>
                    </div>
                    <span class="text-sm text-muted">Demande validée par le Chef</span>
                </li>
            </ul>
            @if ($nextLevel)
                @if ($formationsProgress >= 70)
                <form method="POST" action="{{ route('membre.progression.demander') }}">
                    @csrf
                    <button type="submit" class="w-full py-4 bg-accent text-primary font-bold rounded-2xl hover:bg-accent/90 transition-all shadow-lg shadow-accent/20">
                        Faire ma demande vers {{ $nextLevel['label'] }}
                    </button>
                </form>
                @else
                <button class="w-full py-4 bg-surface text-muted font-bold rounded-2xl cursor-not-allowed" disabled>
                    Faire ma demande
                </button>
                @endif
            @else
            <div class="w-full py-4 bg-success/10 text-success font-bold rounded-2xl text-center">
                <i class="fas fa-trophy mr-2"></i> Niveau maximum atteint !
            </div>
            @endif
        </div>

        <div class="bg-surface p-8 rounded-3xl shadow-sm border border-border">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-primary">Service</h3>
                <div id="session-counter" class="text-3xl font-bold text-accent">0</div>
            </div>
            <p class="text-sm text-muted mb-6">Sessions enregistrées</p>
            <button class="w-full py-3 text-xs font-bold text-primary border border-border rounded-xl hover:bg-surface">Voir le détail des sessions</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function animateCounter(id, target, duration) {
        const el = document.getElementById(id);
        if (!el) return;
        let start = 0;
        const increment = target / (duration / 16);
        function update() {
            start += increment;
            if (start < target) { el.textContent = Math.floor(start); requestAnimationFrame(update); }
            else { el.textContent = target; }
        }
        update();
    }

    window.addEventListener('load', () => {
        setTimeout(() => { animateCounter('session-counter', {{ $formations->where('vu', true)->count() }}, 1000); }, 500);
    });
</script>
@endpush
