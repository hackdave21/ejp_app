@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page_title', 'Vue d\'ensemble')

@section('content')
    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
        <div class="kpi-card bg-surface p-6 rounded-3xl shadow-sm border border-border animate-fade-in-up" style="animation-delay: 0.1s">
            <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-users text-xl"></i>
            </div>
            <p class="text-muted text-xs font-bold uppercase mb-1">Total Membres</p>
            <h3 class="text-3xl font-bold text-primary counter" data-target="{{ $stats['total_membres'] ?? 0 }}">0</h3>
        </div>
        <div class="kpi-card bg-surface p-6 rounded-3xl shadow-sm border border-border animate-fade-in-up" style="animation-delay: 0.2s">
            <div class="w-12 h-12 bg-amber-50 text-accent rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-star text-xl"></i>
            </div>
            <p class="text-muted text-xs font-bold uppercase mb-1">Stars</p>
            <h3 class="text-3xl font-bold text-primary counter" data-target="{{ $stats['total_stars'] ?? 0 }}">0</h3>
        </div>
        <div class="kpi-card bg-surface p-6 rounded-3xl shadow-sm border border-border animate-fade-in-up" style="animation-delay: 0.3s">
            <div class="w-12 h-12 bg-blue-50 text-primary rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-plane text-xl"></i>
            </div>
            <p class="text-muted text-xs font-bold uppercase mb-1">Pilotes</p>
            <h3 class="text-3xl font-bold text-primary counter" data-target="{{ $stats['total_pilotes'] ?? 0 }}">0</h3>
        </div>
        <div class="kpi-card bg-surface p-6 rounded-3xl shadow-sm border border-border animate-fade-in-up" style="animation-delay: 0.4s">
            <div class="w-12 h-12 bg-purple-50 text-purple-500 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-monument text-xl"></i>
            </div>
            <p class="text-muted text-xs font-bold uppercase mb-1">Piliers</p>
            <h3 class="text-3xl font-bold text-primary counter" data-target="{{ $stats['total_piliers'] ?? 0 }}">0</h3>
        </div>
        <div class="kpi-card bg-surface p-6 rounded-3xl shadow-sm border border-border animate-fade-in-up" style="animation-delay: 0.5s">
            <div class="w-12 h-12 bg-green-50 text-success rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-globe text-xl"></i>
            </div>
            <p class="text-muted text-xs font-bold uppercase mb-1">Missionnaires</p>
            <h3 class="text-3xl font-bold text-primary counter" data-target="{{ $stats['total_missionnaires'] ?? 0 }}">0</h3>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <!-- Demandes en attente -->
            <div class="bg-surface p-8 rounded-3xl shadow-sm border border-border">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <h3 class="text-lg font-bold text-primary font-serif">Demandes en attente</h3>
                        <span class="bg-danger text-white text-[10px] font-bold px-2 py-1 rounded-lg">{{ $stats['demandes_en_attente'] ?? 0 }} NOUVELLES</span>
                    </div>
                    <a href="{{ route('admin.demandes.index') }}" class="text-primary/40 hover:text-primary text-sm font-bold">Voir tout</a>
                </div>

                <div class="space-y-4">
                    @forelse ($demandes ?? [] as $demande)
                        <div class="flex items-center justify-between p-4 bg-surface rounded-2xl border border-border hover:border-accent/20 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-primary flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($demande->membre->prenom ?? '?', 0, 1)) }}{{ strtoupper(substr($demande->membre->nom ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-primary">{{ $demande->membre->full_name ?? '—' }}</p>
                                    <p class="text-[10px] text-muted uppercase tracking-wider">Passage vers : <span class="text-primary font-bold">{{ str_replace('_', ' ', $demande->to_level ?? '—') }}</span></p>
                                </div>
                            </div>
                            <span class="text-[10px] text-muted">{{ $demande->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-muted text-sm text-center py-4">Aucune demande en attente.</p>
                    @endforelse
                </div>
            </div>

            <!-- Graphiques -->
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-surface p-8 rounded-3xl shadow-sm border border-border">
                    <h3 class="text-sm font-bold text-muted uppercase tracking-widest mb-8">Répartition par statut</h3>
                    <div class="flex items-end justify-between h-48 gap-4 px-2">
                        <div class="flex-1 flex flex-col items-center gap-3">
                            <div class="w-full bg-accent rounded-t-xl chart-bar" style="height: 0%" data-height="{{ ($stats['total_membres'] ?? 1) > 0 ? (($stats['total_stars'] ?? 0) / max($stats['total_membres'], 1) * 100) . '%' : '0%' }}"></div>
                            <span class="text-[10px] font-bold text-muted">Star</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-3">
                            <div class="w-full bg-primary rounded-t-xl chart-bar" style="height: 0%" data-height="{{ ($stats['total_membres'] ?? 1) > 0 ? (($stats['total_pilotes'] ?? 0) / max($stats['total_membres'], 1) * 100) . '%' : '0%' }}"></div>
                            <span class="text-[10px] font-bold text-muted">Pilote</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-3">
                            <div class="w-full bg-purple-500 rounded-t-xl chart-bar" style="height: 0%" data-height="{{ ($stats['total_membres'] ?? 1) > 0 ? (($stats['total_piliers'] ?? 0) / max($stats['total_membres'], 1) * 100) . '%' : '0%' }}"></div>
                            <span class="text-[10px] font-bold text-muted">Pilier</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-3">
                            <div class="w-full bg-success rounded-t-xl chart-bar" style="height: 0%" data-height="{{ ($stats['total_membres'] ?? 1) > 0 ? (($stats['total_missionnaires'] ?? 0) / max($stats['total_membres'], 1) * 100) . '%' : '0%' }}"></div>
                            <span class="text-[10px] font-bold text-muted">Miss.</span>
                        </div>
                    </div>
                </div>

                <div class="bg-surface p-8 rounded-3xl shadow-sm border border-border">
                    <h3 class="text-sm font-bold text-muted uppercase tracking-widest mb-8">Nouveaux membres / mois</h3>
                    <div class="relative h-48 w-full">
                        <svg viewBox="0 0 400 100" class="w-full h-full overflow-visible">
                            <path d="M0,80 Q50,70 100,50 T200,60 T300,20 T400,40" fill="none" stroke="var(--color-primary)" stroke-width="3" stroke-linecap="round"></path>
                            <circle cx="0" cy="80" r="4" fill="var(--color-primary)"></circle>
                            <circle cx="100" cy="50" r="4" fill="var(--color-primary)"></circle>
                            <circle cx="200" cy="60" r="4" fill="var(--color-primary)"></circle>
                            <circle cx="300" cy="20" r="4" fill="var(--color-primary)"></circle>
                            <circle cx="400" cy="40" r="4" fill="var(--color-primary)"></circle>
                        </svg>
                        <div class="flex justify-between mt-4 text-[10px] font-bold text-muted px-1 uppercase tracking-tighter">
                            <span>Jan</span><span>Fev</span><span>Mar</span><span>Avr</span><span>Mai</span><span>Juin</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Recent Activity -->
        <div class="space-y-8">
            <div class="bg-surface p-8 rounded-3xl shadow-sm border border-border h-full">
                <h3 class="text-lg font-bold text-primary font-serif mb-8">Activité récente</h3>

                <div class="space-y-8 relative">
                    <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-surface"></div>

                    @forelse ($recentMembers ?? [] as $membre)
                        <div class="relative pl-12 animate-slide-in-right" style="animation-delay: 0.{{ $loop->index + 1 }}s">
                            <div class="absolute left-0 w-8 h-8 rounded-full bg-blue-50 text-primary flex items-center justify-center text-xs z-10">
                                <i class="fas fa-plus"></i>
                            </div>
                            <p class="text-sm text-primary font-medium leading-relaxed">
                                <span class="font-bold">{{ $membre->full_name }}</span> a rejoint l'Église
                            </p>
                            <p class="text-[10px] text-muted mt-1 uppercase tracking-wider">{{ $membre->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="text-muted text-sm text-center py-4">Aucune activité récente.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const counters = document.querySelectorAll('.counter');
    const speed = 100;
    counters.forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            const inc = target / speed;
            if (count < target) {
                counter.innerText = Math.ceil(count + inc);
                setTimeout(updateCount, 15);
            } else {
                counter.innerText = target;
            }
        };
        updateCount();
    });

    window.addEventListener('load', () => {
        const bars = document.querySelectorAll('.chart-bar');
        setTimeout(() => {
            bars.forEach(bar => {
                bar.style.height = bar.getAttribute('data-height');
            });
        }, 300);
    });
</script>
@endpush
