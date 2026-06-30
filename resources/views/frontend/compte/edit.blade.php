@extends('layouts.frontend')
@section('title', 'Mon Compte')
@section('page_title', 'Mon Compte')

@section('content')
@if (session('success'))
<div class="mb-6 p-4 bg-success/10 text-success font-bold rounded-2xl border border-success/20 flex items-center gap-3 animate-slide-in-up">
    <i class="fas fa-check-circle text-xl"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

<!-- Success Toast -->
<div id="toast" class="fixed top-6 left-1/2 -translate-x-1/2 z-[100] hidden">
    <div class="bg-primary text-primary-text px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-accent/30 animate-toast-in">
        <div class="w-10 h-10 bg-success rounded-full flex items-center justify-center text-white">
            <i class="fas fa-check"></i>
        </div>
        <div>
            <p class="font-bold">Modifications enregistrées</p>
            <p class="text-xs text-white/60">Votre profil a été mis à jour avec succès.</p>
        </div>
    </div>
</div>

<!-- Section 1: Personal Info -->
<section class="bg-surface rounded-3xl p-8 shadow-sm border border-border">
    <div class="flex items-center justify-between mb-8">
        <h3 class="text-xl font-bold text-primary">Informations personnelles</h3>
        <button id="edit-btn" class="text-accent font-bold hover:underline">Modifier</button>
    </div>

    <div class="flex flex-col md:flex-row gap-12">
        <div class="flex flex-col items-center">
            <div class="relative group cursor-pointer">
                <div class="w-32 h-32 rounded-full bg-accent flex items-center justify-center text-primary text-4xl font-bold border-4 border-white shadow-lg overflow-hidden">
                    {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                        <i class="fas fa-camera text-white"></i>
                    </div>
                </div>
                <svg class="absolute inset-[-6px] w-[144px] h-[144px] hidden group-hover:block" viewBox="0 0 144 144">
                    <circle cx="72" cy="72" r="70" fill="none" stroke="#F5A623" stroke-width="2" class="dashed-border" stroke-dasharray="10" style="animation: border-dance 1s linear infinite;"></circle>
                </svg>
            </div>
            <form method="POST" action="{{ route('membre.compte.updatePhoto') }}" enctype="multipart/form-data" id="photo-form">
                @csrf
                <input type="file" name="photo" id="photo-input" accept="image/jpeg,image/png" class="hidden">
                <p class="mt-4 text-xs text-muted font-medium">JPG, PNG max 2MB</p>
            </form>
        </div>

        <form id="profile-form" method="POST" action="{{ route('membre.compte.update') }}" class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf @method('PUT')
            <div class="space-y-2">
                <label class="text-xs font-bold text-muted uppercase tracking-wider">Nom</label>
                <input type="text" name="nom" value="{{ $user->nom }}" readonly class="w-full py-2 px-0 border-b border-border text-primary font-medium focus:border-accent focus:outline-none transition-all">
            </div>
            <div class="space-y-2">
                <label class="text-xs font-bold text-muted uppercase tracking-wider">Prénom</label>
                <input type="text" name="prenom" value="{{ $user->prenom }}" readonly class="w-full py-2 px-0 border-b border-border text-primary font-medium focus:border-accent focus:outline-none transition-all">
            </div>
            <div class="space-y-2">
                <label class="text-xs font-bold text-muted uppercase tracking-wider">Téléphone</label>
                <input type="text" name="telephone" value="{{ $user->telephone }}" readonly class="w-full py-2 px-0 border-b border-border text-primary font-medium focus:border-accent focus:outline-none transition-all">
            </div>
            <div class="space-y-2">
                <label class="text-xs font-bold text-muted uppercase tracking-wider">E-mail</label>
                <input type="email" name="email" value="{{ $user->email }}" readonly class="w-full py-2 px-0 border-b border-border text-primary font-medium focus:border-accent focus:outline-none transition-all">
            </div>
            <div class="space-y-2">
                <label class="text-xs font-bold text-muted uppercase tracking-wider">Date d'arrivée</label>
                <input type="text" value="{{ $user->created_at->isoFormat('D MMMM YYYY') }}" readonly class="w-full py-2 px-0 text-muted font-medium bg-transparent">
            </div>
            <div class="space-y-2">
                <label class="text-xs font-bold text-muted uppercase tracking-wider">Statut</label>
                <input type="text" value="{{ ucfirst(str_replace('_', ' ', $user->statut ?? 'Membre')) }}" readonly class="w-full py-2 px-0 text-muted font-medium bg-transparent">
            </div>
            <div class="md:col-span-2 pt-4">
                <button type="submit" id="save-btn" class="hidden bg-accent text-primary font-bold px-8 py-3 rounded-xl hover:scale-105 transition-transform shadow-lg shadow-accent/20">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</section>

<!-- Section 2: Security -->
<section class="bg-surface rounded-3xl p-8 shadow-sm border border-border">
    <h3 class="text-xl font-bold text-primary mb-8">Sécurité</h3>
    <form method="POST" action="{{ route('membre.compte.updatePassword') }}" class="max-w-md space-y-6">
        @csrf @method('PUT')
        <div class="space-y-2">
            <label class="text-xs font-bold text-muted uppercase">Ancien mot de passe</label>
            <input type="password" name="current_password" placeholder="••••••••" required class="w-full bg-surface border border-border rounded-xl py-3 px-4 focus:border-accent focus:outline-none">
        </div>
        <div class="space-y-2">
            <label class="text-xs font-bold text-muted uppercase">Nouveau mot de passe</label>
            <input type="password" name="new_password" id="new-password" placeholder="••••••••" required class="w-full bg-surface border border-border rounded-xl py-3 px-4 focus:border-accent focus:outline-none">
            <div class="w-full h-1 bg-surface rounded-full overflow-hidden mt-2">
                <div id="strength-meter" class="strength-bar bg-danger" style="height: 4px; border-radius: 2px; transition: all 0.3s; width: 0%;"></div>
            </div>
            <p id="strength-text" class="text-[10px] font-bold text-muted uppercase">Trop faible</p>
        </div>
        <div class="space-y-2">
            <label class="text-xs font-bold text-muted uppercase">Confirmer le nouveau mot de passe</label>
            <input type="password" name="new_password_confirmation" placeholder="••••••••" required class="w-full bg-surface border border-border rounded-xl py-3 px-4 focus:border-accent focus:outline-none">
        </div>
        <button type="submit" class="w-full py-4 bg-primary text-primary-text font-bold rounded-2xl hover:bg-dark transition-colors">Mettre à jour le mot de passe</button>
    </form>
</section>

<!-- Section 3: Notification Preferences -->
<section class="bg-surface rounded-3xl p-8 shadow-sm border border-border">
    <h3 class="text-xl font-bold text-primary mb-8">Préférences de notifications</h3>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="font-bold text-primary">Rappels d'événements</p>
                <p class="text-sm text-muted">Recevoir des notifications avant le début d'un culte ou événement.</p>
            </div>
            <div class="toggle-switch active" onclick="this.classList.toggle('active')"></div>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <p class="font-bold text-primary">Demandes de progression</p>
                <p class="text-sm text-muted">Être notifié quand ma demande est traitée par un Chef ou l'Admin.</p>
            </div>
            <div class="toggle-switch active" onclick="this.classList.toggle('active')"></div>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <p class="font-bold text-primary">Newsletter EJP</p>
                <p class="text-sm text-muted">Recevoir les actualités hebdomadaires de l'église.</p>
            </div>
            <div class="toggle-switch" onclick="this.classList.toggle('active')"></div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
    const editBtn = document.getElementById('edit-btn');
    const saveBtn = document.getElementById('save-btn');
    const profileInputs = document.querySelectorAll('#profile-form input[name]');

    editBtn?.addEventListener('click', () => {
        const isEditing = editBtn.textContent === 'Annuler';
        if (isEditing) {
            editBtn.textContent = 'Modifier';
            saveBtn.classList.add('hidden');
            profileInputs.forEach((input, index) => {
                if (index < 4) input.setAttribute('readonly', 'true');
            });
        } else {
            editBtn.textContent = 'Annuler';
            saveBtn.classList.remove('hidden');
            profileInputs.forEach((input, index) => {
                if (index < 4) {
                    input.removeAttribute('readonly');
                    if (index === 0) input.focus();
                }
            });
        }
    });

    document.getElementById('profile-form')?.addEventListener('submit', (e) => {
        e.preventDefault();
        editBtn.click();
        const toast = document.getElementById('toast');
        toast.classList.remove('hidden');
        confetti({ particleCount: 100, spread: 70, origin: { y: 0.6 }, colors: ['#F5A623', '#1E3A5F'] });
        setTimeout(() => { toast.classList.add('hidden'); }, 4000);
        e.target.submit();
    });

    const pwInput = document.getElementById('new-password');
    const strengthMeter = document.getElementById('strength-meter');
    const strengthText = document.getElementById('strength-text');

    pwInput?.addEventListener('input', () => {
        const val = pwInput.value;
        let strength = 0;
        if (val.length > 5) strength += 33;
        if (val.match(/[A-Z]/)) strength += 33;
        if (val.match(/[0-9]/)) strength += 34;
        strengthMeter.style.width = strength + '%';
        if (strength < 40) {
            strengthMeter.style.backgroundColor = '#E74C3C';
            strengthText.textContent = 'Trop faible';
            strengthText.style.color = '#E74C3C';
        } else if (strength < 80) {
            strengthMeter.style.backgroundColor = '#F5A623';
            strengthText.textContent = 'Moyen';
            strengthText.style.color = '#F5A623';
        } else {
            strengthMeter.style.backgroundColor = '#27AE60';
            strengthText.textContent = 'Fort';
            strengthText.style.color = '#27AE60';
        }
    });

    document.querySelector('.group')?.addEventListener('click', () => {
        document.getElementById('photo-input')?.click();
    });

    document.getElementById('photo-input')?.addEventListener('change', function() {
        if (this.files.length > 0) {
            document.getElementById('photo-form')?.submit();
        }
    });
</script>
@endpush
