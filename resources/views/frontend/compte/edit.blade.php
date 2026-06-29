@extends('layouts.frontend')
@section('title', 'Mon Compte')
@section('content')
<h1 class="text-2xl font-bold mb-6 dark:text-white">Mon Compte</h1>
@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <h3 class="font-semibold mb-4 dark:text-white">Informations personnelles</h3>
        <form method="POST" action="{{ route('membre.compte.update') }}">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Nom</label><input type="text" name="nom" value="{{ $user->nom }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                    <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Prénom</label><input type="text" name="prenom" value="{{ $user->prenom }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                </div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Téléphone</label><input type="tel" name="telephone" value="{{ $user->telephone }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Email</label><input type="email" name="email" value="{{ $user->email }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Date de naissance</label><input type="date" name="date_naissance" value="{{ $user->date_naissance?->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">Enregistrer</button>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <h3 class="font-semibold mb-4 dark:text-white">Mot de passe</h3>
        <form method="POST" action="{{ route('membre.compte.updatePassword') }}">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Mot de passe actuel</label><input type="password" name="current_password" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Nouveau mot de passe</label><input type="password" name="new_password" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                <div><label class="block text-sm font-medium mb-1 dark:text-gray-300">Confirmer</label><input type="password" name="new_password_confirmation" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white"></div>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">Changer le mot de passe</button>
            </div>
        </form>
    </div>
</div>
@endsection
