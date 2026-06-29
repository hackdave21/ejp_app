<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EJP Portail Membres</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <span class="text-xl font-bold text-primary">EJP Portail</span>
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:text-primary">Connexion</a>
            </div>
        </div>
    </nav>

    <header class="bg-gradient-to-r from-primary to-primary-dark text-white py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">Bienvenue sur l'EJP Portail Membres</h1>
            <p class="text-xl opacity-90 mb-8">Suivez votre progression, accédez aux formations et restez connecté</p>
            <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-white text-primary font-semibold rounded-lg hover:bg-gray-100 transition">Accéder à mon espace</a>
        </div>
    </header>

    <section class="max-w-7xl mx-auto px-4 py-16">
        <h2 class="text-2xl font-bold text-center mb-8 dark:text-white">Votre parcours de progression</h2>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            @foreach ([
                ['label' => 'Nouveau Membre', 'icon' => 'fa-user-plus'],
                ['label' => 'Star', 'icon' => 'fa-star'],
                ['label' => 'Pilote', 'icon' => 'fa-plane'],
                ['label' => 'Pilier', 'icon' => 'fa-building-columns'],
                ['label' => 'Missionnaire', 'icon' => 'fa-globe'],
            ] as $level)
            <div class="text-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                <div class="w-16 h-16 mx-auto rounded-full bg-primary/10 flex items-center justify-center mb-3">
                    <i class="fas {{ $level['icon'] }} text-primary text-xl"></i>
                </div>
                <h3 class="font-semibold dark:text-white">{{ $level['label'] }}</h3>
            </div>
            @endforeach
        </div>
    </section>

    @if ($formations->count() > 0)
    <section class="bg-white dark:bg-gray-800 py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-2xl font-bold text-center mb-8 dark:text-white">Nos formations</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($formations as $module)
                <div class="p-6 border border-gray-200 dark:border-gray-700 rounded-xl">
                    <i class="fas {{ $module->icone ?? 'fa-book' }} text-primary text-2xl mb-3"></i>
                    <h3 class="font-semibold mb-2 dark:text-white">{{ $module->titre }}</h3>
                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-600 dark:bg-blue-900/30 rounded-full">{{ $module->categorie }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if ($evenements->count() > 0)
    <section class="max-w-7xl mx-auto px-4 py-16">
        <h2 class="text-2xl font-bold text-center mb-8 dark:text-white">Prochains événements</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($evenements as $event)
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="text-center mb-4">
                    <p class="text-3xl font-bold text-primary">{{ $event->date_debut->format('d') }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $event->date_debut->format('F Y') }}</p>
                </div>
                <h3 class="font-semibold text-center dark:text-white">{{ $event->titre }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center">{{ $event->lieu }}</p>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <footer class="bg-gray-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-400">&copy; {{ date('Y') }} EJP Portail Membres. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
