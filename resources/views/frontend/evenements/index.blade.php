@extends('layouts.frontend')
@section('title', 'Événements')
@section('page_title', 'Événements')

@section('content')
<section class="bg-white dark:bg-black border border-border dark:border-white/10 rounded-3xl p-8 text-text mb-10 relative overflow-hidden shadow-sm dark:shadow-none animate-fade-in-stagger">
    <div class="relative z-10 max-w-2xl">
        <span class="px-3 py-1 bg-accent/10 text-accent text-[10px] font-bold rounded-full border border-accent/20 uppercase tracking-widest">Événements</span>
        <h1 class="text-3xl sm:text-4xl font-serif text-primary mt-4 mb-2">Événements de l'EJP</h1>
        <p class="text-muted text-sm sm:text-base leading-relaxed">Ne manquez aucun rassemblement de notre communauté. Participez aux cultes, retraites et activités.</p>
    </div>
    <div class="absolute right-0 top-0 h-full w-1/3 bg-accent/5 skew-x-[-20deg] translate-x-1/2 hidden md:block"></div>
</section>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
    @forelse ($evenements as $event)
    <div class="event-card bg-surface rounded-2xl shadow-sm border border-border overflow-hidden transition-all hover:shadow-md animate-fade-in-stagger" style="animation-delay: {{ 0.1 * $loop->iteration }}s">
        <div class="relative aspect-video overflow-hidden bg-primary">
            <div class="absolute inset-0 bg-gradient-to-br from-primary to-dark flex items-center justify-center p-6 text-center">
                <div class="border-2 border-accent/30 p-4 rounded-lg">
                    <h4 class="text-accent font-serif text-lg leading-tight">{{ Str::upper($event->titre) }}</h4>
                    <div class="w-12 h-0.5 bg-accent mx-auto mt-2"></div>
                </div>
            </div>
            <div class="absolute top-4 left-4">
                @if ($event->date_debut->isToday())
                <span class="bg-danger text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest animate-pulse-slow">Aujourd'hui !</span>
                @elseif ($event->date_debut > now())
                <span class="bg-primary text-primary-text text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest">À venir</span>
                @else
                <span class="bg-muted text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest">Terminé</span>
                @endif
            </div>
        </div>
        <div class="p-6">
            <h3 class="text-xl font-bold text-primary font-serif mb-3">{{ $event->titre }}</h3>
            <div class="space-y-2 mb-6">
                <div class="flex items-center gap-2 text-sm text-muted">
                    <i class="far fa-calendar-alt w-4 text-accent"></i>
                    <span>{{ $event->date_debut->isoFormat('dddd D MMMM • HH:mm') }}</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-muted">
                    <i class="fas fa-map-marker-alt w-4 text-accent"></i>
                    <span>{{ $event->lieu }}</span>
                </div>
            </div>
            @if ($event->description)
            <p class="text-muted text-sm mb-6 line-clamp-2 italic">{{ $event->description }}</p>
            @endif
            <button class="open-modal-btn w-full py-3 bg-primary text-primary-text font-bold rounded-xl hover:bg-dark transition-colors"
                data-title="{{ $event->titre }}"
                data-date="{{ $event->date_debut->isoFormat('dddd D MMMM • HH:mm') }}"
                data-location="{{ $event->lieu }}"
                data-description="{{ $event->description ?? 'Aucune description disponible.' }}">En savoir plus</button>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-16 bg-surface rounded-3xl border border-dashed border-border">
        <i class="far fa-calendar-alt text-5xl text-muted mb-4"></i>
        <h3 class="text-xl font-bold text-primary mb-2">Aucun événement pour le moment</h3>
        <p class="text-muted text-sm">Revenez plus tard pour découvrir les prochains événements.</p>
    </div>
    @endforelse
</div>

<section>
    <div class="flex items-center justify-between mb-8">
        <h3 class="text-2xl font-bold text-primary">Archives</h3>
    </div>
    <div class="bg-surface rounded-2xl shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <tbody class="text-sm">
                    @foreach ($evenements->filter(fn($e) => $e->date_fin && $e->date_fin < now()) as $archived)
                    <tr class="border-b border-border hover:bg-surface transition-colors">
                        <td class="p-4 w-12"><i class="fas fa-history text-gray-300"></i></td>
                        <td class="py-4 font-bold text-primary">{{ $archived->titre }}</td>
                        <td class="py-4 text-muted italic">{{ $archived->date_debut->isoFormat('D MMMM YYYY') }}</td>
                        <td class="py-4 text-muted">{{ $archived->lieu }}</td>
                        <td class="py-4 pr-6 text-right">
                            <button class="open-modal-btn text-accent font-bold hover:underline"
                                data-title="{{ $archived->titre }}"
                                data-date="{{ $archived->date_debut->isoFormat('dddd D MMMM • HH:mm') }}"
                                data-location="{{ $archived->lieu }}"
                                data-description="{{ $archived->description ?? 'Aucune description disponible.' }}">Détails</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<div id="event-modal" class="fixed inset-0 z-[100] hidden flex items-end justify-center sm:items-center p-4">
    <div class="modal-overlay absolute inset-0" style="background: rgba(15, 27, 45, 0.8); backdrop-filter: blur(4px);"></div>
    <div class="relative w-full max-w-2xl bg-surface rounded-t-3xl sm:rounded-3xl overflow-hidden shadow-2xl transform transition-all animate-slide-up-modal">
        <div class="h-64 bg-primary relative">
            <div id="modal-bg" class="absolute inset-0 bg-gradient-to-br from-primary to-dark flex items-center justify-center">
                <h2 id="modal-title-hero" class="text-accent text-3xl font-serif text-center px-10"></h2>
            </div>
            <button id="close-modal" class="absolute top-4 right-4 w-10 h-10 bg-black/20 hover:bg-black/40 text-white rounded-full flex items-center justify-center transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-8">
            <div class="flex flex-wrap gap-4 mb-6">
                <div class="flex items-center gap-2 bg-surface px-4 py-2 rounded-xl text-sm font-medium text-muted">
                    <i class="far fa-calendar-alt text-accent"></i>
                    <span id="modal-date"></span>
                </div>
                <div class="flex items-center gap-2 bg-surface px-4 py-2 rounded-xl text-sm font-medium text-muted">
                    <i class="fas fa-map-marker-alt text-accent"></i>
                    <span id="modal-location"></span>
                </div>
            </div>
            <h3 id="modal-title" class="text-2xl font-bold text-primary font-serif mb-4"></h3>
            <p id="modal-description" class="text-muted leading-relaxed mb-8"></p>
            <div class="flex gap-4">
                <button class="flex-1 py-4 bg-primary text-primary-text font-bold rounded-2xl hover:bg-dark transition-colors">S'inscrire</button>
                <button id="share-btn" class="w-16 h-16 flex items-center justify-center border-2 border-border rounded-2xl hover:bg-surface text-primary transition-colors">
                    <i class="fas fa-share-alt"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<div id="toast" class="fixed top-6 left-1/2 -translate-x-1/2 z-[100] hidden">
    <div class="bg-primary text-primary-text px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-accent/30 transition-all">
        <i class="fas fa-check-circle text-accent text-xl"></i>
        <span class="font-medium text-sm">Lien de l'événement copié dans le presse-papiers !</span>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('event-modal');
    const closeModal = document.getElementById('close-modal');
    const openModalBtns = document.querySelectorAll('.open-modal-btn');
    const modalTitleHero = document.getElementById('modal-title-hero');
    const modalTitle = document.getElementById('modal-title');
    const modalDate = document.getElementById('modal-date');
    const modalLocation = document.getElementById('modal-location');
    const modalDescription = document.getElementById('modal-description');
    const modalBg = document.getElementById('modal-bg');

    openModalBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const title = btn.getAttribute('data-title');
            const date = btn.getAttribute('data-date');
            const location = btn.getAttribute('data-location');
            const description = btn.getAttribute('data-description');

            modalTitleHero.textContent = title.toUpperCase();
            modalTitle.textContent = title;
            modalDate.textContent = date;
            modalLocation.textContent = location;
            modalDescription.textContent = description;

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        });
    });

    function handleCloseModal() {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    closeModal.addEventListener('click', handleCloseModal);
    document.querySelector('.modal-overlay')?.addEventListener('click', handleCloseModal);

    document.getElementById('share-btn')?.addEventListener('click', () => {
        navigator.clipboard.writeText(window.location.href).catch(() => {});
        const toast = document.getElementById('toast');
        if (toast) {
            toast.classList.remove('hidden');
            setTimeout(() => { toast.classList.add('hidden'); }, 3000);
        }
    });
</script>
@endpush
