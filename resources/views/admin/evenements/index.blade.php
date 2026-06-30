@extends('layouts.admin')

@section('title', 'Événements')
@section('page_title', '<i class="fas fa-calendar-alt text-accent"></i> Événements')

@section('content')
<div class="flex justify-end">
    <button id="add-event-btn" class="bg-accent text-primary font-bold px-6 py-2.5 rounded-xl hover:scale-105 transition-transform flex items-center gap-2">
        <i class="fas fa-plus"></i> Nouvel Événement
    </button>
</div>

@if (session('success'))
    <div class="p-4 bg-success/10 text-success rounded-2xl border border-success/20 text-sm font-bold">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
    @forelse ($evenements as $event)
    @php
        $now = now();
        if ($event->date_fin < $now) {
            $statut = 'Terminé';
            $statutClass = 'bg-gray-400 text-white';
            $past = true;
        } elseif ($event->capacite_max > 0 && ($event->nombre_participants ?? 0) >= $event->capacite_max) {
            $statut = 'Complet';
            $statutClass = 'bg-danger text-white';
            $past = false;
        } elseif ($event->date_debut > $now) {
            $statut = 'À venir';
            $statutClass = 'bg-success text-white';
            $past = false;
        } else {
            $statut = 'En cours';
            $statutClass = 'bg-accent text-primary';
            $past = false;
        }
        $progress = $event->capacite_max > 0 ? min(($event->nombre_participants ?? 0) / $event->capacite_max * 100, 100) : 0;
    @endphp
    <div class="event-card bg-surface rounded-3xl border border-border overflow-hidden flex flex-col animate-fade-in-up">
        <div class="h-48 event-image-placeholder relative flex items-center justify-center">
            <i class="fas fa-calendar text-white/20 text-6xl"></i>
            <div class="absolute top-4 left-4 bg-surface/90 backdrop-blur-sm px-3 py-1.5 rounded-xl flex flex-col items-center justify-center shadow-lg">
                <span class="text-xs font-bold text-danger uppercase">{{ $event->date_debut->translatedFormat('M') }}</span>
                <span class="text-xl font-bold text-primary leading-none">{{ $event->date_debut->format('d') }}</span>
            </div>
            <div class="absolute top-4 right-4 {{ $statutClass }} text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest shadow-lg">{{ $statut }}</div>
        </div>
        <div class="p-6 flex-1 flex flex-col">
            <div class="mb-4">
                <span class="text-[10px] font-bold text-muted uppercase tracking-widest">{{ $event->type }}</span>
                <h3 class="text-xl font-bold text-primary font-serif leading-tight mt-1">{{ $event->titre }}</h3>
            </div>
            <div class="space-y-2 mb-6 text-sm text-muted">
                <p><i class="fas fa-clock w-5 text-muted"></i> {{ $event->date_debut->format('H:i') }} — {{ $event->date_fin->format('H:i') }}</p>
                <p><i class="fas fa-map-marker-alt w-5 text-muted"></i> {{ $event->lieu }}</p>
            </div>

            <div class="mt-auto pt-6 border-t border-border">
                @if ($event->capacite_max > 0)
                <div class="flex justify-between text-xs mb-2">
                    <span class="font-bold text-muted">Inscrits</span>
                    <span class="font-bold {{ $statut === 'Complet' ? 'text-danger' : 'text-primary' }}">{{ $event->nombre_participants ?? 0 }} / {{ $event->capacite_max }}</span>
                </div>
                <div class="w-full h-2 bg-surface rounded-full overflow-hidden mb-4">
                    <div class="h-full rounded-full {{ $statut === 'Terminé' ? 'bg-gray-400' : ($statut === 'Complet' ? 'bg-danger' : 'bg-accent') }}" style="width: {{ $progress }}%"></div>
                </div>
                @endif
                <div class="flex gap-2">
                    <a href="{{ route('admin.evenements.show', $event) }}" class="flex-1 py-2 bg-surface text-muted font-bold text-xs rounded-xl hover:bg-primary hover:text-primary-text transition-all text-center">Gérer</a>
                    <form method="POST" action="{{ route('admin.evenements.destroy', $event) }}" onsubmit="return confirm('Supprimer cet événement ?')" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-2 bg-surface text-muted font-bold text-xs rounded-xl hover:bg-danger hover:text-white transition-all">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-20 text-muted">
        <i class="fas fa-calendar-times text-5xl mb-4"></i>
        <p class="text-lg font-bold">Aucun événement</p>
        <p class="text-sm">Créez votre premier événement dès maintenant.</p>
    </div>
    @endforelse
</div>

<div id="add-drawer" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-dark/60 backdrop-blur-sm" onclick="document.getElementById('add-drawer').classList.add('hidden')"></div>
    <div class="absolute right-0 top-0 bottom-0 w-full max-w-2xl bg-surface shadow-2xl animate-slide-in-right flex flex-col">
        <header class="p-10 border-b border-border flex items-center justify-between shrink-0">
            <h3 class="text-2xl font-bold text-primary font-serif">Nouvel Événement</h3>
            <button onclick="document.getElementById('add-drawer').classList.add('hidden')" class="w-10 h-10 rounded-full hover:bg-surface text-muted transition-colors"><i class="fas fa-times text-xl"></i></button>
        </header>

        <form method="POST" action="{{ route('admin.evenements.store') }}" class="flex-1 flex flex-col">
            @csrf
            <div class="flex-1 overflow-y-auto p-10 custom-scrollbar space-y-8">
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Titre</label>
                    <input type="text" name="titre" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-1 focus:ring-accent" placeholder="Ex: Retraite des Leaders">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Type</label>
                        <select name="type" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm text-muted outline-none">
                            <option value="concert">Concert</option>
                            <option value="retraite_spirituelle">Retraite Spirituelle</option>
                            <option value="evangelisation">Évangélisation</option>
                            <option value="seminaire">Séminaire</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Capacité Max (0 = illimité)</label>
                        <input type="number" name="capacite_max" class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-1 focus:ring-accent" value="0">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Date & Heure de début</label>
                        <input type="datetime-local" name="date_debut" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-1 focus:ring-accent">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Date & Heure de fin</label>
                        <input type="datetime-local" name="date_fin" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-1 focus:ring-accent">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Lieu</label>
                    <div class="relative">
                        <i class="fas fa-map-marker-alt absolute left-4 top-1/2 -translate-y-1/2 text-muted"></i>
                        <input type="text" name="lieu" required class="w-full bg-bg border-none rounded-xl py-3 pl-10 pr-4 text-sm focus:ring-1 focus:ring-accent" placeholder="Adresse complète">
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Description</label>
                    <textarea name="description" class="w-full bg-bg border-none rounded-2xl py-4 px-6 text-sm text-primary min-h-[150px] focus:ring-1 focus:ring-accent" placeholder="Détaillez le programme..."></textarea>
                </div>
            </div>

            <footer class="p-10 border-t border-border shrink-0">
                <button type="submit" class="w-full py-4 bg-primary text-primary-text font-bold rounded-2xl shadow-lg shadow-primary/20">Publier l'événement</button>
            </footer>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('add-event-btn').onclick = () => {
        document.getElementById('add-drawer').classList.remove('hidden');
    };
</script>
@endpush
