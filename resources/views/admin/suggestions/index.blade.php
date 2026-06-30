@extends('layouts.admin')

@section('title', 'Suggestions')
@section('page_title', 'Boîte à Suggestions')

@section('content')
    @if (session('success'))
        <div class="p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-xl text-sm font-medium">{{ session('success') }}</div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex gap-3">
            <button class="filter-btn px-5 py-2.5 rounded-lg bg-primary text-primary-text font-bold text-sm border border-primary active" data-filter="all">Toutes ({{ $suggestions->count() }})</button>
            <button class="filter-btn px-5 py-2.5 rounded-lg bg-surface text-muted hover:text-primary transition-colors text-sm border border-border" data-filter="eglise">Pour l'EJP ({{ $suggestions->where('categorie', 'eglise')->count() }})</button>
            <button class="filter-btn px-5 py-2.5 rounded-lg bg-surface text-muted hover:text-primary transition-colors text-sm border border-border" data-filter="plateforme">Pour la Plateforme ({{ $suggestions->where('categorie', 'plateforme')->count() }})</button>
        </div>
        <div class="flex gap-2">
            <select id="sort-select" class="bg-surface border border-border rounded-lg px-4 py-2 text-sm text-text focus:outline-none focus:border-accent">
                <option value="recent">Trier par : Plus récentes</option>
                <option value="ancien">Trier par : Plus anciennes</option>
            </select>
        </div>
    </div>

    <div id="suggestions-grid" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse ($suggestions as $suggestion)
            <div class="suggestion-card bg-surface p-6 rounded-2xl border border-border hover:border-accent/30 transition-colors shadow-sm" data-categorie="{{ $suggestion->categorie }}">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="inline-block px-3 py-1 {{ $suggestion->categorie === 'eglise' ? 'bg-purple-500/10 text-purple-500' : 'bg-accent/10 text-accent' }} rounded-full text-[10px] font-bold uppercase tracking-wider mb-2">
                            {{ $suggestion->categorie === 'eglise' ? 'Église EJP' : 'Plateforme Web' }}
                        </span>
                        <h3 class="font-bold text-primary text-lg">{{ Str::limit($suggestion->contenu, 60) }}</h3>
                    </div>
                    <span class="text-xs text-muted whitespace-nowrap ml-4">{{ $suggestion->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-sm text-muted mb-6 leading-relaxed">{{ $suggestion->contenu }}</p>
                <div class="flex items-center justify-between pt-4 border-t border-border">
                    <div class="flex items-center gap-3">
                        @php
                            $initiales = $suggestion->user ? strtoupper(substr($suggestion->user->prenom, 0, 1) . substr($suggestion->user->nom, 0, 1)) : 'A';
                            $bgClass = $suggestion->user ? 'bg-primary text-accent' : 'bg-blue-100 text-blue-600';
                        @endphp
                        <div class="w-8 h-8 rounded-full {{ $bgClass }} flex items-center justify-center text-xs font-bold">{{ $initiales }}</div>
                        <span class="text-sm font-medium text-primary">{{ $suggestion->nom ?? ($suggestion->user?->full_name ?? 'Anonyme') }}</span>
                    </div>
                    <div class="flex gap-2">
                        @if ($suggestion->statut === 'nouveau')
                            <form method="POST" action="{{ route('admin.suggestions.statut', $suggestion) }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="statut" value="lu">
                                <button type="submit" class="p-2 text-success hover:bg-success/10 rounded-lg transition-colors" title="Marquer comme lu">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                        @endif
                        @if ($suggestion->statut !== 'traite')
                            <form method="POST" action="{{ route('admin.suggestions.statut', $suggestion) }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="statut" value="traite">
                                <button type="submit" class="p-2 text-accent hover:bg-amber-50 rounded-lg transition-colors" title="Marquer comme traité">
                                    <i class="fas fa-check-double"></i>
                                </button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.suggestions.destroy', $suggestion) }}" onsubmit="return confirm('Supprimer cette suggestion ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-danger hover:bg-danger/10 rounded-lg transition-colors">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @if ($suggestion->statut === 'traite')
                    <div class="mt-3 pt-3 border-t border-border">
                        <span class="text-xs font-bold text-success flex items-center gap-1"><i class="fas fa-check-double"></i> Traité</span>
                    </div>
                @endif
            </div>
        @empty
            <div class="lg:col-span-2 py-12 text-center text-muted">
                <i class="fas fa-lightbulb text-4xl mb-4 opacity-30"></i>
                <p>Aucune suggestion pour le moment.</p>
            </div>
        @endforelse
    </div>
@endsection

@push('scripts')
<script>
    (function() {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const cards = document.querySelectorAll('.suggestion-card');
        const sortSelect = document.getElementById('sort-select');
        const grid = document.getElementById('suggestions-grid');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => {
                    b.classList.remove('bg-primary', 'text-primary-text', 'border-primary');
                    b.classList.add('bg-surface', 'text-muted', 'border-border');
                });
                btn.classList.add('bg-primary', 'text-primary-text', 'border-primary');
                btn.classList.remove('bg-surface', 'text-muted', 'border-border');

                const filter = btn.dataset.filter;
                cards.forEach(card => {
                    card.style.display = (filter === 'all' || card.dataset.categorie === filter) ? '' : 'none';
                });
            });
        });

        if (sortSelect) {
            sortSelect.addEventListener('change', () => {
                const cardsArr = Array.from(cards);
                const order = sortSelect.value;
                cardsArr.sort((a, b) => {
                    const dateA = a.querySelector('.text-xs.text-muted')?.textContent || '';
                    const dateB = b.querySelector('.text-xs.text-muted')?.textContent || '';
                    return order === 'recent' ? -1 : 1;
                });
                cardsArr.forEach(card => grid.appendChild(card));
            });
        }
    })();
</script>
@endpush
