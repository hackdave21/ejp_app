@extends('layouts.frontend')
@section('title', 'Notifications')
@section('page_title', 'Notifications')

@section('content')
@if (session('success'))
<div class="mb-6 p-4 bg-success/10 text-success font-bold rounded-2xl border border-success/20 flex items-center gap-3">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<section class="bg-white dark:bg-black border border-border dark:border-white/10 rounded-3xl p-8 text-text mb-10 relative overflow-hidden shadow-sm dark:shadow-none animate-fade-in">
    <div class="relative z-10 max-w-2xl">
        <span class="px-3 py-1 bg-accent/10 text-accent text-[10px] font-bold rounded-full border border-accent/20 uppercase tracking-widest">Notifications</span>
        <h1 class="text-3xl sm:text-4xl font-serif text-primary mt-4 mb-2">Centre de Notifications</h1>
        <p class="text-muted text-sm sm:text-base leading-relaxed">Restez informé des dernières activités, de votre progression, des formations et des événements de l'EJP.</p>
    </div>
    <div class="absolute right-0 top-0 h-full w-1/3 bg-accent/5 skew-x-[-20deg] translate-x-1/2 hidden md:block"></div>
</section>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div class="flex flex-wrap gap-2">
        <button class="filter-pill active px-4 py-2 rounded-full border border-border text-sm font-medium transition-all hover:border-primary" data-filter="all">Toutes (<span id="count-all">{{ $notifications->count() }}</span>)</button>
        <button class="filter-pill px-4 py-2 rounded-full border border-border text-sm font-medium transition-all hover:border-primary" data-filter="progression">Progression</button>
        <button class="filter-pill px-4 py-2 rounded-full border border-border text-sm font-medium transition-all hover:border-primary" data-filter="formations">Formations</button>
        <button class="filter-pill px-4 py-2 rounded-full border border-border text-sm font-medium transition-all hover:border-primary" data-filter="evenements">Événements</button>
    </div>
    @if ($notifications->where('lue', false)->count() > 0)
    <form method="POST" action="{{ route('membre.notifications.markAllRead') }}">
        @csrf @method('PUT')
        <button type="submit" class="text-accent text-sm font-bold hover:underline flex items-center gap-2">
            <i class="fas fa-check-double"></i> Tout marquer comme lu
        </button>
    </form>
    @endif
</div>

<div id="notifications-container" class="space-y-4 mb-16">
    @forelse ($notifications as $notif)
    <div class="notification-card {{ !$notif->lue ? 'unread' : '' }} bg-surface border border-border rounded-2xl p-6 flex gap-4 animate-slide-in-up relative" data-category="{{ $notif->categorie ?? 'general' }}" id="notif-{{ $notif->id }}">
        <div class="w-12 h-12 shrink-0 rounded-xl flex items-center justify-center text-lg
            @if($notif->categorie === 'progression') bg-accent/10 text-accent
            @elseif($notif->categorie === 'evenements') bg-success/10 text-success
            @elseif($notif->categorie === 'formations') bg-primary/10 dark:bg-white/10 text-primary dark:text-white
            @else bg-blue-100 text-blue-600 @endif">
            <i class="fas @if($notif->categorie === 'progression') fa-star
                @elseif($notif->categorie === 'evenements') fa-calendar-alt
                @elseif($notif->categorie === 'formations') fa-graduation-cap
                @else fa-info-circle @endif"></i>
        </div>
        <div class="flex-1 pr-6">
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-bold uppercase tracking-wider
                    @if($notif->categorie === 'progression') text-accent
                    @elseif($notif->categorie === 'evenements') text-success
                    @else text-muted @endif">{{ $notif->categorie ?? 'Général' }}</span>
                @if (!$notif->lue)
                <span class="w-2 h-2 rounded-full bg-accent animate-ping" id="badge-pulse-{{ $notif->id }}"></span>
                @endif
            </div>
            <h3 class="font-bold text-primary text-base mb-1">{{ $notif->titre }}</h3>
            <p class="text-muted text-sm leading-relaxed mb-2">{{ $notif->message }}</p>
            <span class="text-xs text-muted block italic">{{ $notif->created_at->diffForHumans() }}</span>
        </div>
        <div class="absolute right-4 top-4 flex gap-2">
            @if (!$notif->lue)
            <form method="POST" action="{{ route('membre.notifications.markRead', $notif) }}" class="inline">
                @csrf @method('PUT')
                <button type="submit" class="mark-read-single text-muted hover:text-accent p-1 text-sm" title="Marquer comme lu"><i class="fas fa-check"></i></button>
            </form>
            @endif
            <form method="POST" action="{{ route('membre.notifications.destroy', $notif) }}" class="inline" onsubmit="return confirm('Supprimer cette notification ?')">
                @csrf @method('DELETE')
                <button type="submit" class="dismiss-notif text-muted hover:text-danger p-1 text-sm" title="Supprimer"><i class="fas fa-times"></i></button>
            </form>
        </div>
    </div>
    @empty
    <div id="empty-state" class="text-center py-20 bg-surface rounded-3xl border border-dashed border-border p-8 max-w-md mx-auto animate-fade-in">
        <div class="w-20 h-20 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-bell-slash text-muted text-3xl"></i>
        </div>
        <h3 class="text-xl font-bold text-primary mb-2">Aucune notification</h3>
        <p class="text-muted text-sm leading-relaxed">Vous êtes parfaitement à jour ! Toutes vos notifications importantes s'afficheront ici.</p>
    </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
    const filterPills = document.querySelectorAll('.filter-pill');
    filterPills.forEach(pill => {
        pill.addEventListener('click', () => {
            filterPills.forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            const category = pill.getAttribute('data-filter');
            const cards = document.querySelectorAll('.notification-card');
            cards.forEach(card => {
                if (category === 'all' || card.getAttribute('data-category') === category) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    function updateUnreadCounts() {
        const total = document.querySelectorAll('.notification-card').length;
        const countAllSpan = document.getElementById('count-all');
        if (countAllSpan) countAllSpan.textContent = total;
    }

    function checkEmptyState() {
        const total = document.querySelectorAll('.notification-card').length;
        const container = document.getElementById('notifications-container');
        const emptyState = document.getElementById('empty-state');
        if (total === 0 && emptyState) {
            if (container) container.classList.add('hidden');
            emptyState.classList.remove('hidden');
        }
    }

    updateUnreadCounts();
</script>
@endpush
