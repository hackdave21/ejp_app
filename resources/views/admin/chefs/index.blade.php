@extends('layouts.admin')

@section('title', 'Chefs & Groupes')
@section('page_title', 'Gestion des Chefs & Groupes')

@section('content')
@if (session('success'))
    <div class="p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-xl text-sm flex items-center gap-2">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<!-- Groups Grid -->
<section>
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-primary font-serif">Groupes actifs</h3>
        <button onclick="openDrawer('createGroupeDrawer')" class="bg-accent text-primary font-bold px-6 py-2.5 rounded-xl hover:scale-105 transition-transform flex items-center gap-2">
            <i class="fas fa-plus"></i> Créer un groupe
        </button>
    </div>
    @if ($groupes->isEmpty())
        <div class="text-center py-12 text-muted">
            <i class="fas fa-users text-4xl mb-4 block"></i>
            <p>Aucun groupe pour le moment.</p>
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
                        <button onclick="openDrawer('editGroupeDrawer{{ $groupe->id }}')" class="w-8 h-8 rounded-lg bg-surface text-muted hover:bg-gray-100 dark:hover:bg-gray-800"><i class="fas fa-edit text-xs"></i></button>
                        <form method="POST" action="{{ route('admin.groupes.destroy', $groupe) }}" onsubmit="return confirm('Supprimer le groupe « {{ $groupe->nom }} » ?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-lg bg-surface text-muted hover:bg-red-50 dark:hover:bg-red-900/20"><i class="fas fa-trash text-xs"></i></button>
                        </form>
                    </div>
                </div>
                <h4 class="text-xl font-bold text-primary mb-1">{{ $groupe->nom }}</h4>
                <p class="text-sm text-muted mb-6">
                    Chef : <span class="text-primary font-medium">{{ $groupe->chef?->user?->full_name ?? '—' }}</span>
                </p>
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
</section>

<!-- Chefs Table -->
<section>
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-primary font-serif">Liste des Chefs</h3>
        <button onclick="openDrawer('createChefDrawer')" class="bg-primary text-primary-text font-bold px-6 py-2.5 rounded-xl hover:scale-105 transition-transform flex items-center gap-2">
            <i class="fas fa-plus"></i> Nouveau Chef
        </button>
    </div>
    <div class="bg-surface rounded-3xl shadow-sm border border-border overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-surface border-b border-border">
                <tr class="text-muted text-[10px] font-bold uppercase tracking-widest">
                    <th class="py-4 px-8">Nom</th>
                    <th class="py-4 px-8">Rôle</th>
                    <th class="py-4 px-8">Téléphone</th>
                    <th class="py-4 px-8">Groupes</th>
                    <th class="py-4 px-8 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                @forelse ($chefs as $chef)
                <tr>
                    <td class="py-4 px-8">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center text-accent font-bold text-xs">
                                {{ strtoupper(substr($chef->user?->prenom ?? '', 0, 1)) }}{{ strtoupper(substr($chef->user?->nom ?? '', 0, 1)) }}
                            </div>
                            <span class="font-bold text-primary">{{ $chef->user?->full_name ?? '—' }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-8">
                        <span class="px-3 py-1 bg-primary text-primary-text text-[10px] font-bold rounded-full">{{ strtoupper($chef->role ?? 'CHEF') }}</span>
                    </td>
                    <td class="py-4 px-8 text-muted">{{ $chef->telephone ?? $chef->user?->telephone ?? '—' }}</td>
                    <td class="py-4 px-8 text-muted">{{ $chef->groupes->pluck('nom')->implode(', ') ?: '—' }}</td>
                    <td class="py-4 px-8 text-right">
                        <button onclick="openDrawer('editChefDrawer{{ $chef->id }}')" class="text-accent font-bold hover:underline text-sm">Gérer</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center text-muted">
                        <i class="fas fa-user-tie text-3xl mb-3 block"></i>
                        Aucun chef trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<!-- Create Groupe Drawer -->
<div id="createGroupeDrawer" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/30" onclick="closeDrawer('createGroupeDrawer')"></div>
    <div class="absolute right-0 top-0 h-full w-full max-w-lg bg-surface shadow-2xl border-l border-border overflow-y-auto custom-scrollbar animate-slide-in-right">
        <div class="p-8">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-bold text-primary font-serif">Nouveau groupe</h3>
                <button onclick="closeDrawer('createGroupeDrawer')" class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-muted hover:text-primary"><i class="fas fa-times"></i></button>
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
                    <button type="button" onclick="closeDrawer('createGroupeDrawer')" class="px-6 py-3 text-muted font-bold hover:text-primary transition-colors">Annuler</button>
                    <button type="submit" class="px-8 py-3 bg-accent text-primary font-bold rounded-xl hover:scale-105 transition-transform">Créer le groupe</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Groupe Drawers -->
@foreach ($groupes as $groupe)
<div id="editGroupeDrawer{{ $groupe->id }}" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/30" onclick="closeDrawer('editGroupeDrawer{{ $groupe->id }}')"></div>
    <div class="absolute right-0 top-0 h-full w-full max-w-lg bg-surface shadow-2xl border-l border-border overflow-y-auto custom-scrollbar animate-slide-in-right">
        <div class="p-8">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-bold text-primary font-serif">Modifier le groupe</h3>
                <button onclick="closeDrawer('editGroupeDrawer{{ $groupe->id }}')" class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-muted hover:text-primary"><i class="fas fa-times"></i></button>
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
                    <button type="button" onclick="closeDrawer('editGroupeDrawer{{ $groupe->id }}')" class="px-6 py-3 text-muted font-bold hover:text-primary transition-colors">Annuler</button>
                    <button type="submit" class="px-8 py-3 bg-accent text-primary font-bold rounded-xl hover:scale-105 transition-transform">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Create Chef Drawer -->
<div id="createChefDrawer" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/30" onclick="closeDrawer('createChefDrawer')"></div>
    <div class="absolute right-0 top-0 h-full w-full max-w-lg bg-surface shadow-2xl border-l border-border overflow-y-auto custom-scrollbar animate-slide-in-right">
        <div class="p-8">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-bold text-primary font-serif">Nouveau chef</h3>
                <button onclick="closeDrawer('createChefDrawer')" class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-muted hover:text-primary"><i class="fas fa-times"></i></button>
            </div>
            <form method="POST" action="{{ route('admin.chefs.store') }}">
                @csrf
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-muted uppercase tracking-widest mb-2">Nom</label>
                            <input type="text" name="nom" required class="w-full px-4 py-3 bg-bg border border-border rounded-xl text-primary placeholder:text-muted focus:outline-none focus:ring-2 focus:ring-accent/30">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-muted uppercase tracking-widest mb-2">Prénom</label>
                            <input type="text" name="prenom" required class="w-full px-4 py-3 bg-bg border border-border rounded-xl text-primary placeholder:text-muted focus:outline-none focus:ring-2 focus:ring-accent/30">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-muted uppercase tracking-widest mb-2">Téléphone</label>
                        <input type="text" name="telephone" required class="w-full px-4 py-3 bg-bg border border-border rounded-xl text-primary placeholder:text-muted focus:outline-none focus:ring-2 focus:ring-accent/30">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-muted uppercase tracking-widest mb-2">Email</label>
                        <input type="email" name="email" class="w-full px-4 py-3 bg-bg border border-border rounded-xl text-primary placeholder:text-muted focus:outline-none focus:ring-2 focus:ring-accent/30">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-muted uppercase tracking-widest mb-2">Mot de passe</label>
                        <input type="password" name="password" required class="w-full px-4 py-3 bg-bg border border-border rounded-xl text-primary placeholder:text-muted focus:outline-none focus:ring-2 focus:ring-accent/30">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-muted uppercase tracking-widest mb-2">Rôle chef</label>
                        <select name="role" required class="w-full px-4 py-3 bg-bg border border-border rounded-xl text-primary focus:outline-none focus:ring-2 focus:ring-accent/30">
                            <option value="chef_de_groupe">Chef de Groupe</option>
                            <option value="sous_chef">Sous-Chef</option>
                            <option value="assistant">Assistant</option>
                        </select>
                    </div>
                </div>
                <div class="mt-8 flex items-center justify-end gap-4">
                    <button type="button" onclick="closeDrawer('createChefDrawer')" class="px-6 py-3 text-muted font-bold hover:text-primary transition-colors">Annuler</button>
                    <button type="submit" class="px-8 py-3 bg-accent text-primary font-bold rounded-xl hover:scale-105 transition-transform">Créer le chef</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Chef Drawers -->
@foreach ($chefs as $chef)
<div id="editChefDrawer{{ $chef->id }}" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/30" onclick="closeDrawer('editChefDrawer{{ $chef->id }}')"></div>
    <div class="absolute right-0 top-0 h-full w-full max-w-lg bg-surface shadow-2xl border-l border-border overflow-y-auto custom-scrollbar animate-slide-in-right">
        <div class="p-8">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-bold text-primary font-serif">Gérer le chef</h3>
                <button onclick="closeDrawer('editChefDrawer{{ $chef->id }}')" class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-muted hover:text-primary"><i class="fas fa-times"></i></button>
            </div>
            <div class="mb-6 flex items-center gap-4 p-4 bg-bg rounded-2xl">
                <div class="w-12 h-12 rounded-xl bg-primary flex items-center justify-center text-accent font-bold">
                    {{ strtoupper(substr($chef->user?->prenom ?? '', 0, 1)) }}{{ strtoupper(substr($chef->user?->nom ?? '', 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-primary">{{ $chef->user?->full_name ?? '—' }}</p>
                    <p class="text-xs text-muted">{{ $chef->user?->email ?? '—' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.chefs.update', $chef) }}">
                @csrf @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-muted uppercase tracking-widest mb-2">Rôle</label>
                        <select name="role" required class="w-full px-4 py-3 bg-bg border border-border rounded-xl text-primary focus:outline-none focus:ring-2 focus:ring-accent/30">
                            <option value="chef_de_groupe" {{ $chef->role == 'chef_de_groupe' ? 'selected' : '' }}>Chef de Groupe</option>
                            <option value="sous_chef" {{ $chef->role == 'sous_chef' ? 'selected' : '' }}>Sous-Chef</option>
                            <option value="assistant" {{ $chef->role == 'assistant' ? 'selected' : '' }}>Assistant</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-muted uppercase tracking-widest mb-2">Statut</label>
                        <select name="statut" class="w-full px-4 py-3 bg-bg border border-border rounded-xl text-primary focus:outline-none focus:ring-2 focus:ring-accent/30">
                            <option value="1" {{ $chef->statut ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ !$chef->statut ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                </div>
                <div class="mt-8 flex items-center justify-end gap-4">
                    <button type="button" onclick="closeDrawer('editChefDrawer{{ $chef->id }}')" class="px-6 py-3 text-muted font-bold hover:text-primary transition-colors">Annuler</button>
                    <button type="submit" class="px-8 py-3 bg-accent text-primary font-bold rounded-xl hover:scale-105 transition-transform">Enregistrer</button>
                </div>
            </form>
            <div class="mt-6 pt-6 border-t border-border">
                <form method="POST" action="{{ route('admin.chefs.destroy', $chef) }}" onsubmit="return confirm('Supprimer définitivement ce chef et son compte utilisateur ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-3 bg-red-50 dark:bg-red-900/20 text-danger font-bold rounded-xl hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-trash"></i> Supprimer ce chef
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script>
    function openDrawer(id) { document.getElementById(id).classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    function closeDrawer(id) { document.getElementById(id).classList.add('hidden'); document.body.style.overflow = ''; }
    window.addEventListener('load', function() {
        var fills = document.querySelectorAll('.progress-fill');
        setTimeout(function() {
            fills.forEach(function(fill) { fill.style.width = fill.getAttribute('data-width'); });
        }, 300);
    });
</script>
@endpush
