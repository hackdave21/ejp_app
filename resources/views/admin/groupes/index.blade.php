@extends('layouts.admin')

@section('title', 'Groupes')
@section('page_title', 'Gestion des Groupes')

@section('content')
@if (session('success'))
    <div class="p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-xl text-sm flex items-center gap-2">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="flex items-center justify-between mb-6">
    <h3 class="text-lg font-bold text-primary font-serif">Tous les groupes</h3>
    <button onclick="openModal('createGroupeModal')" class="bg-accent text-primary font-bold px-6 py-2.5 rounded-xl hover:scale-105 transition-transform flex items-center gap-2">
        <i class="fas fa-plus"></i> Créer un groupe
    </button>
</div>

@if ($groupes->isEmpty())
    <div class="text-center py-16 text-muted">
        <i class="fas fa-users text-5xl mb-4 block"></i>
        <p class="text-lg">Aucun groupe pour le moment.</p>
    </div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @foreach ($groupes as $groupe)
        @php
            $colors = [
                ['bg' => 'bg-blue-50', 'text' => 'text-primary'],
                ['bg' => 'bg-amber-50', 'text' => 'text-accent'],
                ['bg' => 'bg-purple-50', 'text' => 'text-purple-500'],
                ['bg' => 'bg-green-50', 'text' => 'text-green-600'],
                ['bg' => 'bg-red-50', 'text' => 'text-red-500'],
                ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-500'],
            ];
            $c = $colors[$loop->index % count($colors)];
            $membresCount = $groupe->membres->count();
            $capacite = $groupe->capacite_max ?: 1;
            $membresPct = min(100, round($membresCount / $capacite * 100));
        @endphp
        <div class="group-card bg-surface p-6 rounded-3xl shadow-sm border border-border animate-fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s">
            <div class="flex items-center justify-between mb-6">
                <div class="w-12 h-12 {{ $c['bg'] }} {{ $c['text'] }} rounded-2xl flex items-center justify-center font-bold text-lg">
                    {{ strtoupper(substr($groupe->nom, 0, 1)) }}
                </div>
                <div class="flex gap-2">
                    <button onclick="openModal('editGroupeModal{{ $groupe->id }}')" class="w-8 h-8 rounded-lg bg-surface text-muted hover:bg-gray-100 dark:hover:bg-gray-800"><i class="fas fa-edit text-xs"></i></button>
                    <form method="POST" action="{{ route('admin.groupes.destroy', $groupe) }}" onsubmit="return confirm('Supprimer le groupe « {{ $groupe->nom }} » ?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-8 h-8 rounded-lg bg-surface text-muted hover:bg-red-50 dark:hover:bg-red-900/20"><i class="fas fa-trash text-xs"></i></button>
                    </form>
                </div>
            </div>
            <h4 class="text-xl font-bold text-primary mb-1">{{ $groupe->nom }}</h4>
            <p class="text-sm text-muted mb-4">Chef : <span class="text-primary font-medium">{{ $groupe->chef?->user?->full_name ?? '—' }}</span></p>
            @if ($groupe->description)
                <p class="text-sm text-muted mb-4">{{ Str::limit($groupe->description, 80) }}</p>
            @endif
            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between text-xs mb-2">
                        <span class="text-muted font-bold uppercase">Membres</span>
                        <span class="text-primary font-bold">{{ $membresCount }} / {{ $groupe->capacite_max ?? '∞' }}</span>
                    </div>
                    <div class="w-full h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                        <div class="progress-fill h-full bg-accent rounded-full" style="width: 0%" data-width="{{ $membresPct }}%"></div>
                    </div>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-muted font-bold uppercase">Progression</span>
                    <span class="text-success font-bold">{{ $membresPct }}%</span>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endif

<!-- Create Groupe Modal -->
<div id="createGroupeModal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/30" onclick="closeModal('createGroupeModal')"></div>
    <div class="relative bg-surface rounded-3xl p-8 w-full max-w-lg mx-4 shadow-2xl border border-border animate-fade-in-up">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-xl font-bold text-primary font-serif">Nouveau groupe</h3>
            <button onclick="closeModal('createGroupeModal')" class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-muted hover:text-primary"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.groupes.store') }}">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-muted uppercase tracking-widest mb-2">Nom du groupe</label>
                    <input type="text" name="nom" required class="w-full px-4 py-3 bg-bg border border-border rounded-xl text-primary placeholder:text-muted focus:outline-none focus:ring-2 focus:ring-accent/30">
                </div>
                <div>
                    <label class="block text-sm font-bold text-muted uppercase tracking-widest mb-2">Chef responsable</label>
                    <select name="chef_id" required class="w-full px-4 py-3 bg-bg border border-border rounded-xl text-primary focus:outline-none focus:ring-2 focus:ring-accent/30">
                        <option value="">Sélectionner un chef...</option>
                        @foreach ($chefs as $chef)
                        <option value="{{ $chef->id }}">{{ $chef->user?->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-muted uppercase tracking-widest mb-2">Capacité maximale</label>
                    <input type="number" name="capacite_max" value="50" min="1" class="w-full px-4 py-3 bg-bg border border-border rounded-xl text-primary focus:outline-none focus:ring-2 focus:ring-accent/30">
                </div>
                <div>
                    <label class="block text-sm font-bold text-muted uppercase tracking-widest mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-3 bg-bg border border-border rounded-xl text-primary placeholder:text-muted focus:outline-none focus:ring-2 focus:ring-accent/30"></textarea>
                </div>
            </div>
            <div class="mt-8 flex items-center justify-end gap-4">
                <button type="button" onclick="closeModal('createGroupeModal')" class="px-6 py-3 text-muted font-bold hover:text-primary transition-colors">Annuler</button>
                <button type="submit" class="px-8 py-3 bg-accent text-primary font-bold rounded-xl hover:scale-105 transition-transform">Créer le groupe</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Groupe Modals -->
@foreach ($groupes as $groupe)
<div id="editGroupeModal{{ $groupe->id }}" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/30" onclick="closeModal('editGroupeModal{{ $groupe->id }}')"></div>
    <div class="relative bg-surface rounded-3xl p-8 w-full max-w-lg mx-4 shadow-2xl border border-border animate-fade-in-up">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-xl font-bold text-primary font-serif">Modifier le groupe</h3>
            <button onclick="closeModal('editGroupeModal{{ $groupe->id }}')" class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-muted hover:text-primary"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.groupes.update', $groupe) }}">
            @csrf @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-muted uppercase tracking-widest mb-2">Nom du groupe</label>
                    <input type="text" name="nom" value="{{ $groupe->nom }}" required class="w-full px-4 py-3 bg-bg border border-border rounded-xl text-primary focus:outline-none focus:ring-2 focus:ring-accent/30">
                </div>
                <div>
                    <label class="block text-sm font-bold text-muted uppercase tracking-widest mb-2">Chef responsable</label>
                    <select name="chef_id" required class="w-full px-4 py-3 bg-bg border border-border rounded-xl text-primary focus:outline-none focus:ring-2 focus:ring-accent/30">
                        @foreach ($chefs as $chef)
                        <option value="{{ $chef->id }}" {{ $groupe->chef_id == $chef->id ? 'selected' : '' }}>{{ $chef->user?->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-muted uppercase tracking-widest mb-2">Capacité maximale</label>
                    <input type="number" name="capacite_max" value="{{ $groupe->capacite_max }}" min="1" class="w-full px-4 py-3 bg-bg border border-border rounded-xl text-primary focus:outline-none focus:ring-2 focus:ring-accent/30">
                </div>
                <div>
                    <label class="block text-sm font-bold text-muted uppercase tracking-widest mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-3 bg-bg border border-border rounded-xl text-primary focus:outline-none focus:ring-2 focus:ring-accent/30">{{ $groupe->description }}</textarea>
                </div>
            </div>
            <div class="mt-8 flex items-center justify-end gap-4">
                <button type="button" onclick="closeModal('editGroupeModal{{ $groupe->id }}')" class="px-6 py-3 text-muted font-bold hover:text-primary transition-colors">Annuler</button>
                <button type="submit" class="px-8 py-3 bg-accent text-primary font-bold rounded-xl hover:scale-105 transition-transform">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.body.style.overflow = ''; }
    window.addEventListener('load', function() {
        var fills = document.querySelectorAll('.progress-fill');
        setTimeout(function() {
            fills.forEach(function(fill) { fill.style.width = fill.getAttribute('data-width'); });
        }, 300);
    });
</script>
@endpush
