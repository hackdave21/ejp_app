<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Connexion</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md mx-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold dark:text-white">EJP Admin</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Connectez-vous à votre espace</p>
            </div>
            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-lg text-sm">{{ $errors->first('identifiant') }}</div>
                @endif
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Identifiant Admin</label>
                        <input type="text" name="identifiant" required class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary" placeholder="Email ou téléphone">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mot de passe</label>
                        <input type="password" name="password" required class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary" placeholder="Mot de passe sécurisé">
                    </div>
                </div>
                <button type="submit" class="w-full mt-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark font-medium transition-colors">
                    Se connecter
                </button>
            </form>
        </div>
    </div>
</body>
</html>
