@extends('layouts.admin')
@section('title', 'Paramètres')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold dark:text-white">Paramètres</h1>
</div>
@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg">{{ session('success') }}</div>
@endif
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
    <form method="POST" action="{{ route('admin.parametres.update') }}">
        @csrf @method('PUT')
        <div class="space-y-6">
            <div>
                <h3 class="font-semibold mb-4 dark:text-white">Général</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Nom de l'application</label><input type="text" name="app_nom" value="{{ $params['app_nom'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                    <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Email de contact</label><input type="email" name="email_contact" value="{{ $params['email_contact'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                    <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">WhatsApp Assistance</label><input type="text" name="whatsapp_assistance" value="{{ $params['whatsapp_assistance'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                </div>
            </div>
            <div>
                <h3 class="font-semibold mb-4 dark:text-white">Fonctionnalités</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-3">
                        <input type="hidden" name="inscriptions_ouvertes" value="false">
                        <input type="checkbox" name="inscriptions_ouvertes" value="true" {{ ($params['inscriptions_ouvertes'] ?? 'true') === 'true' ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-600">
                        <span class="dark:text-white">Inscriptions ouvertes</span>
                    </label>
                    <label class="flex items-center gap-3">
                        <input type="hidden" name="mode_maintenance" value="false">
                        <input type="checkbox" name="mode_maintenance" value="true" {{ ($params['mode_maintenance'] ?? 'false') === 'true' ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-600">
                        <span class="dark:text-white">Mode maintenance</span>
                    </label>
                    <label class="flex items-center gap-3">
                        <input type="hidden" name="caracteres_speciaux_obligatoires" value="false">
                        <input type="checkbox" name="caracteres_speciaux_obligatoires" value="true" {{ ($params['caracteres_speciaux_obligatoires'] ?? 'true') === 'true' ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-600">
                        <span class="dark:text-white">Caractères spéciaux obligatoires (mot de passe)</span>
                    </label>
                    <label class="flex items-center gap-3">
                        <input type="hidden" name="2fa_chefs" value="false">
                        <input type="checkbox" name="2fa_chefs" value="true" {{ ($params['2fa_chefs'] ?? 'false') === 'true' ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-600">
                        <span class="dark:text-white">2FA pour les chefs</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">Enregistrer</button>
        </div>
    </form>
</div>
@endsection
