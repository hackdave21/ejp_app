@extends('layouts.admin')

@section('title', 'Communications')
@section('page_title', 'Communications')

@section('content')
    @if (session('success'))
        <div class="p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-xl text-sm font-medium">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-surface p-6 rounded-3xl border border-border shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 text-primary rounded-2xl flex items-center justify-center text-xl">
                <i class="fas fa-envelope"></i>
            </div>
            <div>
                <p class="text-[10px] text-muted font-bold uppercase tracking-widest">Campagnes</p>
                <p class="text-2xl font-bold text-primary">{{ $campagnes->count() }}</p>
            </div>
        </div>
        <div class="bg-surface p-6 rounded-3xl border border-border shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-green-50 text-success rounded-2xl flex items-center justify-center text-xl">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <p class="text-[10px] text-muted font-bold uppercase tracking-widest">Envoyées</p>
                <p class="text-2xl font-bold text-primary">{{ $campagnes->where('statut', 'envoye')->count() }}</p>
            </div>
        </div>
        <div class="bg-surface p-6 rounded-3xl border border-border shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-50 text-accent rounded-2xl flex items-center justify-center text-xl">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <p class="text-[10px] text-muted font-bold uppercase tracking-widest">Programmées</p>
                <p class="text-2xl font-bold text-primary">{{ $campagnes->where('statut', 'programme')->count() }}</p>
            </div>
        </div>
        <div class="bg-surface p-6 rounded-3xl border border-border shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-50 text-purple-500 rounded-2xl flex items-center justify-center text-xl">
                <i class="fas fa-file"></i>
            </div>
            <div>
                <p class="text-[10px] text-muted font-bold uppercase tracking-widest">Brouillons</p>
                <p class="text-2xl font-bold text-primary">{{ $campagnes->where('statut', 'brouillon')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between">
        <div></div>
        <button id="add-comm-btn" class="bg-accent text-primary font-bold px-6 py-2.5 rounded-xl hover:scale-105 transition-transform flex items-center gap-2">
            <i class="fas fa-paper-plane"></i> Nouvelle Campagne
        </button>
    </div>

    <section class="bg-surface rounded-3xl shadow-sm border border-border overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-surface/50">
                <tr class="text-muted text-[10px] font-bold uppercase tracking-widest">
                    <th class="py-4 px-8">Campagne / Objet</th>
                    <th class="py-4 px-8">Canal</th>
                    <th class="py-4 px-8">Cible</th>
                    <th class="py-4 px-8">Date</th>
                    <th class="py-4 px-8">Statut</th>
                    <th class="py-4 px-8 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($campagnes as $c)
                    <tr class="hover:bg-surface transition-colors group">
                        <td class="py-4 px-8">
                            <p class="font-bold text-primary">{{ $c->titre }}</p>
                            <p class="text-[10px] text-muted">{{ Str::limit($c->contenu, 60) }}</p>
                        </td>
                        <td class="py-4 px-8">
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-bold rounded-full uppercase tracking-widest">
                                {{ str_replace('_', ' ', $c->canal) }}
                            </span>
                        </td>
                        <td class="py-4 px-8 text-muted">{{ str_replace('_', ' ', $c->audience_cible) }}</td>
                        <td class="py-4 px-8 text-muted">{{ $c->date_envoi?->format('d/m/Y H:i') ?? '—' }}</td>
                        <td class="py-4 px-8">
                            @php
                                $statutClass = match($c->statut) {
                                    'envoye' => 'bg-success/10 text-success',
                                    'programme' => 'bg-amber-50 text-amber-500',
                                    default => 'bg-surface text-muted',
                                };
                            @endphp
                            <span class="px-3 py-1 {{ $statutClass }} text-[10px] font-bold rounded-full uppercase tracking-widest">{{ $c->statut }}</span>
                        </td>
                        <td class="py-4 px-8 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="#" class="edit-comm-btn w-8 h-8 rounded-lg bg-surface text-muted hover:bg-primary hover:text-primary-text transition-all flex items-center justify-center" data-id="{{ $c->id }}" data-titre="{{ $c->titre }}" data-canal="{{ $c->canal }}" data-audience="{{ $c->audience_cible }}" data-contenu="{{ $c->contenu }}" data-date="{{ $c->date_envoi?->format('Y-m-d\TH:i') }}" data-statut="{{ $c->statut }}">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.communications.destroy', $c) }}" onsubmit="return confirm('Supprimer cette campagne ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-danger/10 text-danger hover:bg-danger hover:text-white transition-all flex items-center justify-center">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-muted">Aucune campagne pour le moment.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <!-- Add Campaign Drawer -->
    <div id="add-drawer" class="fixed inset-0 z-[100] hidden">
        <div class="drawer-overlay absolute inset-0"></div>
        <div class="absolute right-0 top-0 bottom-0 w-full max-w-3xl bg-surface shadow-2xl animate-slide-in-right flex flex-col">
            <header class="p-10 border-b border-border flex items-center justify-between shrink-0">
                <h3 class="text-2xl font-bold text-primary font-serif">Nouvelle Campagne</h3>
                <button id="close-drawer" class="w-10 h-10 rounded-full hover:bg-surface text-muted transition-colors"><i class="fas fa-times text-xl"></i></button>
            </header>
            <form method="POST" action="{{ route('admin.communications.store') }}" class="flex-1 overflow-y-auto p-10 custom-scrollbar space-y-8">
                @csrf
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Canal</label>
                        <select name="canal" class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm text-muted outline-none">
                            <option value="email">Email</option>
                            <option value="notification_push">Notification Push</option>
                            <option value="annonce_in_app">Annonce In-App</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Audience Cible</label>
                        <select name="audience_cible" class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm text-muted outline-none">
                            <option value="tous_membres">Tous les membres</option>
                            <option value="tous_chefs">Tous les Chefs</option>
                            <option value="groupe_specifique">Groupe Spécifique</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Objet / Titre</label>
                    <input type="text" name="titre" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-1 focus:ring-accent" placeholder="Titre accrocheur...">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Contenu du message</label>
                    <textarea name="contenu" required rows="6" class="w-full bg-bg border-none rounded-xl py-4 px-6 text-sm text-primary min-h-[200px] outline-none resize-none focus:ring-1 focus:ring-accent" placeholder="Rédigez votre message ici."></textarea>
                </div>
                <div class="p-6 bg-blue-50 rounded-2xl flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-primary">Programmer l'envoi</h4>
                        <p class="text-xs text-muted mt-1">Définissez une date et heure pour un envoi différé.</p>
                    </div>
                    <input type="datetime-local" name="date_envoi" class="bg-surface border-none rounded-xl py-2 px-4 text-sm text-muted shadow-sm focus:ring-1 focus:ring-accent">
                </div>
                <footer class="flex gap-4 pt-4">
                    <button type="button" id="cancel-drawer" class="flex-1 py-4 bg-surface border border-border text-muted font-bold rounded-2xl hover:bg-surface transition-colors">Annuler</button>
                    <button type="submit" class="flex-[2] py-4 bg-primary text-primary-text font-bold rounded-2xl hover:bg-dark transition-colors shadow-lg shadow-primary/20 flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i> Créer la campagne
                    </button>
                </footer>
            </form>
        </div>
    </div>

    <!-- Edit Campaign Drawer -->
    <div id="edit-drawer" class="fixed inset-0 z-[100] hidden">
        <div class="drawer-overlay absolute inset-0"></div>
        <div class="absolute right-0 top-0 bottom-0 w-full max-w-3xl bg-surface shadow-2xl animate-slide-in-right flex flex-col">
            <header class="p-10 border-b border-border flex items-center justify-between shrink-0">
                <h3 class="text-2xl font-bold text-primary font-serif">Modifier la Campagne</h3>
                <button id="close-edit-drawer" class="w-10 h-10 rounded-full hover:bg-surface text-muted transition-colors"><i class="fas fa-times text-xl"></i></button>
            </header>
            <form id="edit-comm-form" method="POST" action="" class="flex-1 overflow-y-auto p-10 custom-scrollbar space-y-8">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Canal</label>
                        <select name="canal" id="edit-canal" class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm text-muted outline-none">
                            <option value="email">Email</option>
                            <option value="notification_push">Notification Push</option>
                            <option value="annonce_in_app">Annonce In-App</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Audience Cible</label>
                        <select name="audience_cible" id="edit-audience" class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm text-muted outline-none">
                            <option value="tous_membres">Tous les membres</option>
                            <option value="tous_chefs">Tous les Chefs</option>
                            <option value="groupe_specifique">Groupe Spécifique</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Objet / Titre</label>
                    <input type="text" name="titre" id="edit-titre" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-1 focus:ring-accent">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Contenu du message</label>
                    <textarea name="contenu" id="edit-contenu" required rows="6" class="w-full bg-bg border-none rounded-xl py-4 px-6 text-sm text-primary min-h-[200px] outline-none resize-none focus:ring-1 focus:ring-accent"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div class="p-6 bg-blue-50 rounded-2xl flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-bold text-primary">Date d'envoi</h4>
                            <p class="text-xs text-muted mt-1">Programmation de l'envoi.</p>
                        </div>
                        <input type="datetime-local" name="date_envoi" id="edit-date" class="bg-surface border-none rounded-xl py-2 px-4 text-sm text-muted shadow-sm focus:ring-1 focus:ring-accent">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Statut</label>
                        <select name="statut" id="edit-statut" class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm text-muted outline-none">
                            <option value="brouillon">Brouillon</option>
                            <option value="programme">Programmé</option>
                            <option value="envoye">Envoyé</option>
                        </select>
                    </div>
                </div>
                <footer class="flex gap-4 pt-4">
                    <button type="button" id="cancel-edit-drawer" class="flex-1 py-4 bg-surface border border-border text-muted font-bold rounded-2xl hover:bg-surface transition-colors">Annuler</button>
                    <button type="submit" class="flex-[2] py-4 bg-primary text-primary-text font-bold rounded-2xl hover:bg-dark transition-colors shadow-lg shadow-primary/20 flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </footer>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<style>
    .drawer-overlay { background: rgba(15, 27, 45, 0.6); backdrop-filter: blur(4px); }
    tr:hover .group-hover\:opacity-100 { opacity: 1; }
</style>
<script>
    const addBtn = document.getElementById('add-comm-btn');
    const drawer = document.getElementById('add-drawer');
    const closeDrawer = document.getElementById('close-drawer');
    const cancelDrawer = document.getElementById('cancel-drawer');
    if (addBtn) addBtn.onclick = () => { drawer.classList.remove('hidden'); document.body.style.overflow = 'hidden'; };
    if (closeDrawer) closeDrawer.onclick = () => { drawer.classList.add('hidden'); document.body.style.overflow = ''; };
    if (cancelDrawer) cancelDrawer.onclick = () => { drawer.classList.add('hidden'); document.body.style.overflow = ''; };
    if (drawer) drawer.querySelector('.drawer-overlay').onclick = () => { drawer.classList.add('hidden'); document.body.style.overflow = ''; };

    const editDrawer = document.getElementById('edit-drawer');
    const closeEditDrawer = document.getElementById('close-edit-drawer');
    const cancelEditDrawer = document.getElementById('cancel-edit-drawer');
    const editForm = document.getElementById('edit-comm-form');
    if (closeEditDrawer) closeEditDrawer.onclick = () => { editDrawer.classList.add('hidden'); document.body.style.overflow = ''; };
    if (cancelEditDrawer) cancelEditDrawer.onclick = () => { editDrawer.classList.add('hidden'); document.body.style.overflow = ''; };
    if (editDrawer) editDrawer.querySelector('.drawer-overlay').onclick = () => { editDrawer.classList.add('hidden'); document.body.style.overflow = ''; };

    document.querySelectorAll('.edit-comm-btn').forEach(btn => {
        btn.onclick = (e) => {
            e.preventDefault();
            document.getElementById('edit-titre').value = btn.dataset.titre;
            document.getElementById('edit-canal').value = btn.dataset.canal;
            document.getElementById('edit-audience').value = btn.dataset.audience;
            document.getElementById('edit-contenu').value = btn.dataset.contenu;
            document.getElementById('edit-date').value = btn.dataset.date || '';
            document.getElementById('edit-statut').value = btn.dataset.statut;
            editForm.action = '/admin/communications/' + btn.dataset.id;
            editDrawer.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };
    });
</script>
@endpush
