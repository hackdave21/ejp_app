@extends('layouts.admin')

@section('title', 'Gestion des Membres')

@section('page_title', 'Membres')

@section('content')
    @if (session('success'))
        <div class="p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-xl text-sm font-medium">{{ session('success') }}</div>
    @endif

    <div class="flex flex-col md:flex-row gap-4 items-center justify-between bg-surface p-4 rounded-2xl shadow-sm border border-border">
        <div class="relative w-full md:w-96">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-muted"></i>
            <input type="text" id="search-input" placeholder="Rechercher par nom, téléphone..." class="w-full bg-bg border-none rounded-xl py-2.5 pl-12 pr-4 text-sm focus:ring-2 focus:ring-accent/20">
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <select id="statut-filter" class="flex-1 md:w-40 bg-bg border-none rounded-xl px-4 py-2.5 text-sm text-muted outline-none">
                <option value="">Tous les statuts</option>
                <option value="nouveau_membre">Nouveau Membre</option>
                <option value="star">Star ⭐</option>
                <option value="pilote">Pilote ✈️</option>
                <option value="pilier">Pilier 🏛️</option>
                <option value="missionnaire">Missionnaire 🌍</option>
            </select>
            <select id="chef-filter" class="flex-1 md:w-40 bg-bg border-none rounded-xl px-4 py-2.5 text-sm text-muted outline-none">
                <option value="">Tous les Chefs</option>
                @foreach ($chefs as $chef)
                    <option value="{{ $chef->id }}">{{ $chef->user->full_name }}</option>
                @endforeach
            </select>
            <button class="bg-bg text-muted px-4 py-2.5 rounded-xl hover:bg-gray-200 transition-colors" title="Exporter">
                <i class="fas fa-download"></i>
            </button>
        </div>
    </div>

    <div class="flex items-center justify-between">
        <div class="flex gap-6">
            <div>
                <p class="text-[10px] font-bold text-muted uppercase">Total</p>
                <p class="text-sm font-bold text-primary">{{ $membres->count() }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-muted uppercase">Ce mois</p>
                <p class="text-sm font-bold text-success">+{{ $membres->where('date_entree', '>=', now()->startOfMonth())->count() }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-muted uppercase">En attente</p>
                <p class="text-sm font-bold text-danger">{{ $membres->where('statut', 'nouveau_membre')->count() }}</p>
            </div>
        </div>
        <button id="add-member-btn" class="bg-accent text-primary font-bold px-6 py-2.5 rounded-xl hover:scale-105 transition-transform flex items-center gap-2">
            <i class="fas fa-plus"></i> Ajouter un membre
        </button>
    </div>

    <div class="bg-surface rounded-3xl shadow-sm border border-border flex flex-col overflow-hidden">
        <div class="overflow-y-auto custom-scrollbar">
            <table class="w-full text-left">
                <thead class="sticky top-0 bg-surface z-10">
                    <tr class="text-muted text-[10px] font-bold uppercase tracking-[0.2em] border-b border-border">
                        <th class="py-6 px-6">Avatar</th>
                        <th class="py-6 px-6">Nom & Prénom</th>
                        <th class="py-6 px-6">Statut</th>
                        <th class="py-6 px-6">Chef</th>
                        <th class="py-6 px-6">Date Entrée</th>
                        <th class="py-6 px-6">Sessions</th>
                        <th class="py-6 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="member-table-body" class="text-sm divide-y divide-gray-50">
                    @forelse ($membres as $membre)
                        <tr class="hover:bg-surface transition-colors group member-row" data-statut="{{ $membre->statut }}" data-chef="{{ $membre->chef_responsable_id }}" data-nom="{{ strtolower($membre->nom) }}" data-prenom="{{ strtolower($membre->prenom) }}" data-telephone="{{ $membre->telephone }}">
                            <td class="py-4 px-6">
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-primary flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($membre->prenom, 0, 1)) }}{{ strtoupper(substr($membre->nom, 0, 1)) }}
                                </div>
                            </td>
                            <td class="py-4 px-6 font-bold text-primary">{{ $membre->full_name }}</td>
                            <td class="py-4 px-6">
                                @php
                                    $statutBadge = [
                                        'nouveau_membre' => ['class' => 'bg-blue-100 text-blue-600', 'label' => 'Nouveau Membre'],
                                        'star' => ['class' => 'bg-accent/20 text-accent', 'label' => '⭐ STAR'],
                                        'pilote' => ['class' => 'bg-primary/10 text-primary', 'label' => '✈️ PILOTE'],
                                        'pilier' => ['class' => 'bg-purple-100 text-purple-600', 'label' => '🏛️ PILIER'],
                                        'missionnaire' => ['class' => 'bg-green-100 text-green-600', 'label' => '🌍 MISSIONNAIRE'],
                                    ];
                                @endphp
                                <span class="px-3 py-1 text-[10px] font-bold rounded-full {{ $statutBadge[$membre->statut]['class'] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $statutBadge[$membre->statut]['label'] ?? $membre->statut }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-muted">{{ $membre->chefResponsable?->user?->full_name ?? '—' }}</td>
                            <td class="py-4 px-6 text-muted">{{ $membre->date_entree?->format('d/m/Y') ?? '—' }}</td>
                            <td class="py-4 px-6 font-bold text-primary">{{ $membre->groupes->count() }}</td>
                            <td class="py-4 px-6 text-right">
                                <div class="row-actions opacity-0 flex justify-end gap-2 transition-opacity">
                                    <button class="open-detail-btn w-8 h-8 rounded-lg bg-blue-50 text-primary hover:bg-primary hover:text-primary-text transition-all" data-id="{{ $membre->id }}" data-nom="{{ $membre->nom }}" data-prenom="{{ $membre->prenom }}" data-email="{{ $membre->email }}" data-telephone="{{ $membre->telephone }}" data-statut="{{ $membre->statut }}" data-chef="{{ $membre->chefResponsable?->user?->full_name }}" data-date-entree="{{ $membre->date_entree?->format('d/m/Y') }}">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    <button class="open-edit-btn w-8 h-8 rounded-lg bg-surface text-muted hover:bg-gray-200 transition-all" data-id="{{ $membre->id }}">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button class="delete-member-btn w-8 h-8 rounded-lg bg-red-50 text-danger hover:bg-danger hover:text-white transition-all" data-action="{{ route('admin.membres.destroy', $membre) }}">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-muted">Aucun membre trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Member Drawer -->
    <div id="add-drawer" class="fixed inset-0 z-[100] hidden">
        <div class="drawer-overlay absolute inset-0"></div>
        <div class="absolute right-0 top-0 bottom-0 w-full max-w-md bg-surface shadow-2xl animate-slide-in-right p-10 overflow-y-auto custom-scrollbar">
            <div class="flex items-center justify-between mb-10">
                <h3 class="text-2xl font-bold text-primary font-serif">Nouveau Membre</h3>
                <button id="close-drawer" class="w-10 h-10 rounded-full hover:bg-surface text-muted transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            <form method="POST" action="{{ route('admin.membres.store') }}" class="space-y-6">
                @csrf
                <div class="flex flex-col items-center mb-8">
                    <div class="w-24 h-24 rounded-full bg-surface border-2 border-dashed border-border flex flex-col items-center justify-center text-gray-300 cursor-pointer hover:border-accent hover:text-accent transition-all">
                        <i class="fas fa-camera text-2xl mb-1"></i>
                        <span class="text-[10px] font-bold">PHOTO</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Nom</label>
                        <input type="text" name="nom" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-accent/20" placeholder="Ex: Doe">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Prénom</label>
                        <input type="text" name="prenom" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-accent/20" placeholder="Ex: John">
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Téléphone *</label>
                    <input type="tel" name="telephone" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-accent/20" placeholder="+225 ...">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">E-mail</label>
                    <input type="email" name="email" class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-accent/20" placeholder="john.doe@email.com">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Mot de passe</label>
                    <input type="password" name="password" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-accent/20" placeholder="••••••••">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Chef Responsable</label>
                    <select name="chef_responsable_id" class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm text-muted outline-none">
                        <option value="">Sélectionner un Chef</option>
                        @foreach ($chefs as $chef)
                            <option value="{{ $chef->id }}">{{ $chef->user->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="p-4 bg-blue-50 rounded-2xl">
                    <p class="text-[10px] font-bold text-primary uppercase mb-2">Statut Initial</p>
                    <div class="flex items-center gap-2 text-primary font-bold">
                        <i class="fas fa-user-plus"></i> Nouveau Membre
                    </div>
                </div>
                <div class="pt-6">
                    <button type="submit" class="w-full py-4 bg-primary text-primary-text font-bold rounded-2xl hover:bg-dark transition-colors shadow-lg shadow-primary/20">Créer le compte</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Member Drawer -->
    <div id="edit-drawer" class="fixed inset-0 z-[100] hidden">
        <div class="drawer-overlay absolute inset-0"></div>
        <div class="absolute right-0 top-0 bottom-0 w-full max-w-md bg-surface shadow-2xl animate-slide-in-right p-10 overflow-y-auto custom-scrollbar">
            <div class="flex items-center justify-between mb-10">
                <h3 class="text-2xl font-bold text-primary font-serif">Modifier le Membre</h3>
                <button id="close-edit-drawer" class="w-10 h-10 rounded-full hover:bg-surface text-muted transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            <form id="edit-member-form" method="POST" action="" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="flex flex-col items-center mb-8">
                    <div class="w-24 h-24 rounded-full bg-surface border-2 border-dashed border-border flex flex-col items-center justify-center text-gray-300 cursor-pointer hover:border-accent hover:text-accent transition-all">
                        <i class="fas fa-camera text-2xl mb-1"></i>
                        <span class="text-[10px] font-bold">PHOTO</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Nom</label>
                        <input type="text" name="nom" id="edit-nom" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-accent/20" placeholder="Ex: Doe">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Prénom</label>
                        <input type="text" name="prenom" id="edit-prenom" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-accent/20" placeholder="Ex: John">
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Téléphone *</label>
                    <input type="tel" name="telephone" id="edit-telephone" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-accent/20" placeholder="+225 ...">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">E-mail</label>
                    <input type="email" name="email" id="edit-email" class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-accent/20" placeholder="john.doe@email.com">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Statut</label>
                    <select name="statut" id="edit-statut" class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm text-muted outline-none">
                        <option value="nouveau_membre">Nouveau Membre</option>
                        <option value="star">Star ⭐</option>
                        <option value="pilote">Pilote ✈️</option>
                        <option value="pilier">Pilier 🏛️</option>
                        <option value="missionnaire">Missionnaire 🌍</option>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Chef Responsable</label>
                    <select name="chef_responsable_id" id="edit-chef" class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm text-muted outline-none">
                        <option value="">Sélectionner un Chef</option>
                        @foreach ($chefs as $chef)
                            <option value="{{ $chef->id }}">{{ $chef->user->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="pt-6">
                    <button type="submit" class="w-full py-4 bg-primary text-primary-text font-bold rounded-2xl hover:bg-dark transition-colors shadow-lg shadow-primary/20">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Member Detail Modal -->
    <div id="detail-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="modal-overlay absolute inset-0 bg-dark/80 backdrop-blur-sm animate-fade-in"></div>
        <div class="relative w-full max-w-3xl bg-surface rounded-3xl overflow-hidden shadow-2xl animate-fade-in">
            <header class="p-8 pb-0 border-b border-border">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 rounded-full bg-accent text-primary flex items-center justify-center font-bold text-2xl shadow-lg border-4 border-white" id="detail-initials">—</div>
                        <div>
                            <h2 class="text-2xl font-bold text-primary font-serif" id="detail-name">—</h2>
                            <p class="text-sm text-muted" id="detail-meta">—</p>
                        </div>
                    </div>
                    <button id="close-modal" class="w-10 h-10 rounded-full hover:bg-surface text-muted"><i class="fas fa-times"></i></button>
                </div>
                <div class="flex gap-8">
                    <button class="tab-btn active pb-4 text-xs font-bold uppercase tracking-widest text-primary">ℹ️ Infos</button>
                    <button class="tab-btn pb-4 text-xs font-bold uppercase tracking-widest text-muted hover:text-primary transition-colors">🎓 Formations</button>
                    <button class="tab-btn pb-4 text-xs font-bold uppercase tracking-widest text-muted hover:text-primary transition-colors"><i class="fas fa-chart-line text-accent"></i> Progression</button>
                    <button class="tab-btn pb-4 text-xs font-bold uppercase tracking-widest text-muted hover:text-primary transition-colors"><i class="fas fa-file-alt text-accent"></i> Notes</button>
                </div>
            </header>
            <div class="p-8 max-h-[500px] overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div><p class="text-[10px] font-bold text-muted uppercase mb-1">Téléphone</p><p class="text-sm font-medium text-primary" id="detail-telephone">—</p></div>
                        <div><p class="text-[10px] font-bold text-muted uppercase mb-1">E-mail</p><p class="text-sm font-medium text-primary" id="detail-email">—</p></div>
                    </div>
                    <div class="space-y-4">
                        <div><p class="text-[10px] font-bold text-muted uppercase mb-1">Statut Actuel</p><span class="px-3 py-1 text-[10px] font-bold rounded-full" id="detail-statut-badge">—</span></div>
                        <div><p class="text-[10px] font-bold text-muted uppercase mb-1">Chef Responsable</p><p class="text-sm font-medium text-primary" id="detail-chef">—</p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="modal-overlay absolute inset-0 bg-dark/80 backdrop-blur-sm animate-fade-in"></div>
        <div class="relative w-full max-w-md bg-surface rounded-3xl p-8 shadow-2xl animate-fade-in text-center">
            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-trash text-2xl text-danger"></i>
            </div>
            <h3 class="text-xl font-bold text-primary font-serif mb-2">Confirmer la suppression</h3>
            <p class="text-sm text-muted mb-8">Êtes-vous sûr de vouloir supprimer ce membre ? Cette action est irréversible.</p>
            <form id="delete-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="flex gap-4">
                    <button type="button" id="cancel-delete" class="flex-1 py-3 bg-surface text-muted font-bold rounded-2xl hover:bg-gray-100 transition-colors border border-border">Annuler</button>
                    <button type="submit" class="flex-1 py-3 bg-danger text-white font-bold rounded-2xl hover:bg-red-700 transition-colors">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<style>
    .drawer-overlay { background: rgba(15, 27, 45, 0.6); backdrop-filter: blur(4px); }
    .modal-overlay { background: rgba(15, 27, 45, 0.6); backdrop-filter: blur(4px); }
    .tab-btn.active { border-bottom: 2px solid #F5A623; color: #1E3A5F; font-weight: 700; }
    tr:hover .row-actions { opacity: 1; }
</style>
<script>
    // Add Drawer
    const addBtn = document.getElementById('add-member-btn');
    const drawer = document.getElementById('add-drawer');
    const closeDrawer = document.getElementById('close-drawer');
    if (addBtn) addBtn.onclick = () => { drawer.classList.remove('hidden'); document.body.style.overflow = 'hidden'; };
    if (closeDrawer) closeDrawer.onclick = () => { drawer.classList.add('hidden'); document.body.style.overflow = ''; };
    if (drawer) drawer.querySelector('.drawer-overlay').onclick = () => { drawer.classList.add('hidden'); document.body.style.overflow = ''; };

    // Edit Drawer
    const editDrawer = document.getElementById('edit-drawer');
    const closeEditDrawer = document.getElementById('close-edit-drawer');
    const editForm = document.getElementById('edit-member-form');
    if (closeEditDrawer) closeEditDrawer.onclick = () => { editDrawer.classList.add('hidden'); document.body.style.overflow = ''; };
    if (editDrawer) editDrawer.querySelector('.drawer-overlay').onclick = () => { editDrawer.classList.add('hidden'); document.body.style.overflow = ''; };
    document.querySelectorAll('.open-edit-btn').forEach(btn => {
        btn.onclick = () => {
            const id = btn.dataset.id;
            fetch('/admin/membres/' + id)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('edit-nom').value = data.nom;
                    document.getElementById('edit-prenom').value = data.prenom;
                    document.getElementById('edit-telephone').value = data.telephone;
                    document.getElementById('edit-email').value = data.email || '';
                    document.getElementById('edit-statut').value = data.statut;
                    document.getElementById('edit-chef').value = data.chef_responsable_id || '';
                    editForm.action = '/admin/membres/' + id;
                    editDrawer.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
        };
    });

    // Detail Modal
    const detailModal = document.getElementById('detail-modal');
    const closeModal = document.getElementById('close-modal');
    if (closeModal) closeModal.onclick = () => { detailModal.classList.add('hidden'); document.body.style.overflow = ''; };
    if (detailModal) detailModal.querySelector('.modal-overlay').onclick = () => { detailModal.classList.add('hidden'); document.body.style.overflow = ''; };

    const statutBadgeMap = {
        'nouveau_membre': { class: 'bg-blue-100 text-blue-600', label: 'Nouveau Membre' },
        'star': { class: 'bg-accent/20 text-accent', label: '⭐ STAR' },
        'pilote': { class: 'bg-primary/10 text-primary', label: '✈️ PILOTE' },
        'pilier': { class: 'bg-purple-100 text-purple-600', label: '🏛️ PILIER' },
        'missionnaire': { class: 'bg-green-100 text-green-600', label: '🌍 MISSIONNAIRE' },
    };

    document.querySelectorAll('.open-detail-btn').forEach(btn => {
        btn.onclick = () => {
            const prenom = btn.dataset.prenom;
            const nom = btn.dataset.nom;
            const statut = btn.dataset.statut;
            document.getElementById('detail-initials').textContent = (prenom.charAt(0) + nom.charAt(0)).toUpperCase();
            document.getElementById('detail-name').textContent = prenom + ' ' + nom;
            document.getElementById('detail-meta').textContent = 'Membre depuis le ' + (btn.dataset.dateEntree || '—');
            document.getElementById('detail-telephone').textContent = btn.dataset.telephone || '—';
            document.getElementById('detail-email').textContent = btn.dataset.email || '—';
            document.getElementById('detail-chef').textContent = btn.dataset.chef || '—';
            const badge = statutBadgeMap[statut] || { class: 'bg-gray-100 text-gray-600', label: statut };
            const statutEl = document.getElementById('detail-statut-badge');
            statutEl.className = 'px-3 py-1 text-[10px] font-bold rounded-full ' + badge.class;
            statutEl.textContent = badge.label;
            detailModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };
    });

    // Delete Modal
    const deleteModal = document.getElementById('delete-modal');
    const deleteForm = document.getElementById('delete-form');
    const cancelDelete = document.getElementById('cancel-delete');
    if (cancelDelete) cancelDelete.onclick = () => { deleteModal.classList.add('hidden'); document.body.style.overflow = ''; };

    document.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-member-btn');
        if (deleteBtn) {
            e.preventDefault();
            deleteForm.action = deleteBtn.dataset.action;
            deleteModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    });

    // Tabs
    document.querySelectorAll('.tab-btn').forEach(tab => {
        tab.onclick = () => {
            document.querySelectorAll('.tab-btn').forEach(t => {
                t.classList.remove('active');
                t.classList.add('text-muted');
            });
            tab.classList.add('active');
            tab.classList.remove('text-muted');
        };
    });

    // Search & Filter
    const searchInput = document.getElementById('search-input');
    const statutFilter = document.getElementById('statut-filter');
    const chefFilter = document.getElementById('chef-filter');

    function filterTable() {
        const search = (searchInput ? searchInput.value.toLowerCase() : '');
        const statut = (statutFilter ? statutFilter.value : '');
        const chef = (chefFilter ? chefFilter.value : '');
        document.querySelectorAll('.member-row').forEach(row => {
            const nom = (row.dataset.prenom + ' ' + row.dataset.nom);
            const tel = (row.dataset.telephone || '');
            const matchesSearch = !search || nom.includes(search) || tel.includes(search);
            const matchesStatut = !statut || row.dataset.statut === statut;
            const matchesChef = !chef || row.dataset.chef === chef;
            row.style.display = (matchesSearch && matchesStatut && matchesChef) ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', filterTable);
    if (statutFilter) statutFilter.addEventListener('change', filterTable);
    if (chefFilter) chefFilter.addEventListener('change', filterTable);
</script>
@endpush
