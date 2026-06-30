@extends('layouts.admin')

@section('title', 'Paramètres')
@section('page_title', 'Paramètres Système')

@section('content')
    @if (session('success'))
        <div class="p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-xl text-sm font-medium">{{ session('success') }}</div>
    @endif

    <div class="flex-1 flex overflow-hidden bg-surface rounded-3xl shadow-sm border border-border">
        <div class="w-64 bg-surface border-r border-border p-6 flex flex-col gap-2 shrink-0 overflow-y-auto">
            <button class="settings-tab active w-full text-left px-4 py-3 rounded-xl font-medium text-sm transition-colors bg-primary text-primary-text" data-target="general">
                <i class="fas fa-cog w-6 text-center"></i> Général
            </button>
            <button class="settings-tab w-full text-left px-4 py-3 rounded-xl font-medium text-sm text-muted transition-colors" data-target="security">
                <i class="fas fa-shield-alt w-6 text-center"></i> Sécurité
            </button>
            <button class="settings-tab w-full text-left px-4 py-3 rounded-xl font-medium text-sm text-muted transition-colors" data-target="notifications">
                <i class="fas fa-bell w-6 text-center"></i> Notifications
            </button>
            <button class="settings-tab w-full text-left px-4 py-3 rounded-xl font-medium text-sm text-muted transition-colors" data-target="roles">
                <i class="fas fa-user-shield w-6 text-center"></i> Rôles & Accès
            </button>
        </div>

        <div class="flex-1 p-8 overflow-y-auto custom-scrollbar">
            <form method="POST" action="{{ route('admin.parametres.update') }}">
                @csrf @method('PUT')

                <div id="tab-general" class="tab-content active max-w-3xl space-y-8">
                    <div>
                        <h3 class="text-lg font-bold text-primary font-serif mb-1">Configuration de base</h3>
                        <p class="text-xs text-muted mb-6">Informations principales de la plateforme EJP.</p>
                        <div class="space-y-6 bg-surface p-6 rounded-3xl border border-border shadow-sm">
                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Nom de l'application</label>
                                    <input type="text" name="app_nom" value="{{ $params['app_nom'] ?? 'EJP Portail Membres' }}" class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-1 focus:ring-accent">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Email de contact (Support)</label>
                                    <input type="email" name="email_contact" value="{{ $params['email_contact'] ?? 'contact@ejp.ci' }}" class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-1 focus:ring-accent">
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Numéro WhatsApp (Assistance)</label>
                                <input type="tel" name="whatsapp_assistance" value="{{ $params['whatsapp_assistance'] ?? '+225 00 00 00 00 00' }}" class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-1 focus:ring-accent">
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-primary font-serif mb-1">État du Système</h3>
                        <p class="text-xs text-muted mb-6">Contrôlez l'accès global à l'application.</p>
                        <div class="bg-surface rounded-3xl border border-border shadow-sm divide-y divide-gray-50">
                            <div class="p-6 flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-primary text-sm">Ouverture des inscriptions</p>
                                    <p class="text-xs text-muted">Autoriser de nouveaux membres à créer un compte.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="inscriptions_ouvertes" value="false">
                                    <input type="checkbox" name="inscriptions_ouvertes" value="true" class="sr-only peer toggle-input" {{ ($params['inscriptions_ouvertes'] ?? 'true') === 'true' ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-success"></div>
                                </label>
                            </div>
                            <div class="p-6 flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-primary text-sm">Mode Maintenance</p>
                                    <p class="text-xs text-muted">Rendre l'application inaccessible aux membres (les admins conservent l'accès).</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="mode_maintenance" value="false">
                                    <input type="checkbox" name="mode_maintenance" value="true" class="sr-only peer toggle-input" {{ ($params['mode_maintenance'] ?? 'false') === 'true' ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-success"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="tab-security" class="tab-content hidden max-w-3xl space-y-8">
                    <div>
                        <h3 class="text-lg font-bold text-primary font-serif mb-1">Politique de Mot de Passe</h3>
                        <p class="text-xs text-muted mb-6">Exigences de sécurité pour les comptes membres et admins.</p>
                        <div class="bg-surface rounded-3xl border border-border shadow-sm divide-y divide-gray-50">
                            <div class="p-6 flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-primary text-sm">Caractères spéciaux obligatoires</p>
                                    <p class="text-xs text-muted">Exiger au moins un symbole (!@#$%).</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="caracteres_speciaux_obligatoires" value="false">
                                    <input type="checkbox" name="caracteres_speciaux_obligatoires" value="true" class="sr-only peer toggle-input" {{ ($params['caracteres_speciaux_obligatoires'] ?? 'true') === 'true' ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-success"></div>
                                </label>
                            </div>
                            <div class="p-6 flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-primary text-sm">Double Authentification (2FA) pour les Chefs</p>
                                    <p class="text-xs text-muted">Obligatoire pour accéder aux données des groupes.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="2fa_chefs" value="false">
                                    <input type="checkbox" name="2fa_chefs" value="true" class="sr-only peer toggle-input" {{ ($params['2fa_chefs'] ?? 'false') === 'true' ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-success"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 bg-danger/10 border border-danger/20 rounded-3xl">
                        <h4 class="text-danger font-bold mb-2"><i class="fas fa-exclamation-triangle"></i> Déconnexion Globale</h4>
                        <p class="text-xs text-danger mb-4 opacity-80">Déconnecter tous les utilisateurs actifs actuellement (y compris vous-même). Utile en cas de faille critique.</p>
                        <button type="button" class="bg-danger text-white font-bold text-xs px-4 py-2 rounded-lg hover:bg-red-700 transition-colors" onclick="alert('Fonctionnalité à implémenter.')">Forcer la déconnexion</button>
                    </div>
                </div>

                <div id="tab-notifications" class="tab-content hidden max-w-3xl">
                    <div class="p-10 text-center text-muted">
                        <i class="fas fa-tools text-4xl mb-4 opacity-50"></i>
                        <p class="font-medium">Paramètres des notifications en cours de développement.</p>
                    </div>
                </div>

                <div id="tab-roles" class="tab-content hidden max-w-3xl">
                    <div class="p-10 text-center text-muted">
                        <i class="fas fa-tools text-4xl mb-4 opacity-50"></i>
                        <p class="font-medium">Gestion fine des permissions en cours de développement.</p>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-primary text-primary-text font-bold px-8 py-3 rounded-xl hover:bg-dark transition-colors flex items-center gap-2 shadow-lg shadow-primary/20">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<style>
    .settings-tab.active { background-color: #1E3A5F; color: white; }
    .tab-content.active { display: block; }
    .tab-content.hidden { display: none; }
</style>
<script>
    (function() {
        const tabs = document.querySelectorAll('.settings-tab');
        const contents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => {
                    t.classList.remove('active', 'bg-primary', 'text-primary-text');
                    t.classList.add('text-muted');
                });
                contents.forEach(c => {
                    c.classList.add('hidden');
                    c.classList.remove('active');
                });

                tab.classList.add('active', 'bg-primary', 'text-primary-text');
                tab.classList.remove('text-muted');

                const targetId = 'tab-' + tab.getAttribute('data-target');
                const target = document.getElementById(targetId);
                if (target) {
                    target.classList.remove('hidden');
                    target.classList.add('active');
                }
            });
        });
    })();
</script>
@endpush
