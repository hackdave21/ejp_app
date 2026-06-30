@extends('layouts.admin')
@section('title', 'Gestion des Formations')
@section('page_title', 'Formations')
@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-muted">{{ $modules->count() }} module(s) de formation</p>
    <button id="add-module-btn" class="bg-accent text-primary font-bold px-6 py-2.5 rounded-xl hover:scale-105 transition-transform flex items-center gap-2">
        <i class="fas fa-plus"></i> Créer un module
    </button>
</div>

@if (session('success'))
    <div class="mb-6 p-4 bg-success/10 text-success rounded-2xl border border-success/20 flex items-center gap-3">
        <i class="fas fa-check-circle"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
@endif

@forelse ($modules as $module)
<div class="accordion-item bg-surface rounded-3xl shadow-sm border border-border overflow-hidden {{ $loop->first ? 'active' : '' }}">
    <div class="p-6 flex items-center justify-between cursor-pointer group" onclick="this.parentElement.classList.toggle('active')">
        <div class="flex items-center gap-6">
            <div class="w-14 h-14 bg-blue-50 text-primary rounded-2xl flex items-center justify-center text-xl">
                <i class="fas {{ $module->icone ?? 'fa-book' }}"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-primary">{{ $module->titre }}</h3>
                @if ($module->description)
                    <p class="text-xs text-muted font-medium mt-0.5">{{ Str::limit($module->description, 80) }}</p>
                @endif
                @if ($module->duree)
                    <p class="text-xs text-muted font-medium mt-0.5">{{ $module->duree }}</p>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="hidden md:flex gap-2">
                <button onclick="openEditDrawer({{ $module->id }}, '{{ addslashes($module->titre) }}', '{{ $module->categorie }}', '{{ addslashes($module->icone) }}')" class="px-4 py-2 bg-surface text-muted text-[10px] font-bold uppercase rounded-lg hover:bg-border transition-colors">Éditer</button>
                <form method="POST" action="{{ route('admin.formations.destroy', $module) }}" onsubmit="return confirm('Supprimer ce module ?')" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-danger/10 text-danger text-[10px] font-bold uppercase rounded-lg hover:bg-danger hover:text-white transition-colors"><i class="fas fa-trash"></i></button>
                </form>
            </div>
            <i class="fas fa-chevron-down chevron transition-transform text-muted"></i>
        </div>
    </div>
    <div class="accordion-content border-t border-border bg-[#FBFBFF]">
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between p-4 bg-surface rounded-2xl border border-border">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-surface rounded-lg flex items-center justify-center text-muted">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-[10px] font-bold text-muted uppercase">{{ $module->categorie ?? 'N/A' }}</span>
                            <span class="text-[10px] font-bold text-muted">&middot; {{ $module->created_at->format('d/m/Y') }}</span>
                        </div>
                        <h4 class="text-sm font-bold text-primary">{{ $module->titre }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@empty
<div class="bg-surface rounded-3xl shadow-sm border border-border p-12 text-center">
    <div class="w-20 h-20 bg-primary/5 rounded-full flex items-center justify-center text-accent text-3xl mx-auto mb-4">
        <i class="fas fa-graduation-cap"></i>
    </div>
    <h3 class="text-xl font-bold text-primary mb-2">Aucun module de formation</h3>
    <p class="text-muted mb-6">Commencez par créer votre premier module de formation.</p>
    <button id="empty-add-btn" class="bg-accent text-primary font-bold px-6 py-3 rounded-xl hover:scale-105 transition-transform inline-flex items-center gap-2">
        <i class="fas fa-plus"></i> Créer un module
    </button>
</div>
@endforelse

<div id="add-drawer" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-dark/60 backdrop-blur-sm animate-fade-in" onclick="closeAddDrawer()"></div>
    <div class="absolute right-0 top-0 bottom-0 w-full max-w-md bg-surface shadow-2xl animate-slide-in-right p-10 overflow-y-auto custom-scrollbar">
        <div class="flex items-center justify-between mb-10">
            <h3 class="text-2xl font-bold text-primary font-serif">Créer un module</h3>
            <button onclick="closeAddDrawer()" class="w-10 h-10 rounded-xl bg-bg flex items-center justify-center text-muted hover:bg-border transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.formations.store') }}" class="space-y-6">
            @csrf
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Titre du module</label>
                <input type="text" name="titre" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-accent/20" placeholder="Ex: Vie en Christ">
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Catégorie</label>
                <select name="categorie" class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm text-muted outline-none focus:ring-2 focus:ring-accent/20">
                    <option value="fondements">Fondements</option>
                    <option value="leadership">Leadership</option>
                    <option value="ministeres">Ministères</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Icône (FontAwesome)</label>
                <input type="text" name="icone" class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-accent/20" placeholder="Ex: fa-bible">
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Description</label>
                <textarea name="description" rows="3" class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-accent/20" placeholder="Description du module..."></textarea>
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Durée estimée</label>
                <input type="text" name="duree" class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-accent/20" placeholder="Ex: 45 min">
            </div>
            <div class="pt-6">
                <button type="submit" class="w-full py-4 bg-primary text-primary-text font-bold rounded-2xl hover:bg-dark transition-colors shadow-lg shadow-primary/20">Créer le module</button>
            </div>
        </form>
    </div>
</div>

<div id="edit-drawer" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-dark/60 backdrop-blur-sm animate-fade-in" onclick="closeEditDrawer()"></div>
    <div class="absolute right-0 top-0 bottom-0 w-full max-w-md bg-surface shadow-2xl animate-slide-in-right p-10 overflow-y-auto custom-scrollbar">
        <div class="flex items-center justify-between mb-10">
            <h3 class="text-2xl font-bold text-primary font-serif">Modifier le module</h3>
            <button onclick="closeEditDrawer()" class="w-10 h-10 rounded-xl bg-bg flex items-center justify-center text-muted hover:bg-border transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="" class="space-y-6" id="edit-form">
            @csrf @method('PUT')
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Titre du module</label>
                <input type="text" name="titre" id="edit-titre" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-accent/20">
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Catégorie</label>
                <select name="categorie" id="edit-categorie" class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm text-muted outline-none focus:ring-2 focus:ring-accent/20">
                    <option value="fondements">Fondements</option>
                    <option value="leadership">Leadership</option>
                    <option value="ministeres">Ministères</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Icône (FontAwesome)</label>
                <input type="text" name="icone" id="edit-icone" class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-accent/20">
            </div>
            <div class="pt-6">
                <button type="submit" class="w-full py-4 bg-primary text-primary-text font-bold rounded-2xl hover:bg-dark transition-colors shadow-lg shadow-primary/20">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .accordion-content { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; }
    .accordion-item.active .accordion-content { max-height: 1000px; transition: max-height 0.5s ease-in; }
    .accordion-item.active .chevron { transform: rotate(180deg); }
    .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
    .animate-slide-in-right { animation: slideInRight 0.4s cubic-bezier(0.165, 0.84, 0.44, 1) forwards; }
    @keyframes fadeIn { 0% { opacity: 0; } 100% { opacity: 1; } }
    @keyframes slideInRight { 0% { transform: translateX(100%); } 100% { transform: translateX(0); } }
</style>
<script>
    function openAddDrawer() {
        document.getElementById('add-drawer').classList.remove('hidden');
    }
    function closeAddDrawer() {
        document.getElementById('add-drawer').classList.add('hidden');
    }
    function closeEditDrawer() {
        document.getElementById('edit-drawer').classList.add('hidden');
    }
    function openEditDrawer(id, titre, categorie, icone) {
        document.getElementById('edit-form').action = '{{ url('admin/formations') }}/' + id;
        document.getElementById('edit-titre').value = titre;
        document.getElementById('edit-categorie').value = categorie;
        document.getElementById('edit-icone').value = icone;
        document.getElementById('edit-drawer').classList.remove('hidden');
    }
    document.getElementById('add-module-btn')?.addEventListener('click', openAddDrawer);
    document.getElementById('empty-add-btn')?.addEventListener('click', openAddDrawer);
</script>
@endpush
