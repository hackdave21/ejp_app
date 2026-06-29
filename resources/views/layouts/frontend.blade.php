<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EJP Portail')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('membre.dashboard') }}" class="text-xl font-bold text-primary">EJP Portail</a>
                <div class="flex items-center gap-4">
                    <a href="{{ route('membre.formations.index') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:text-primary"><i class="fas fa-graduation-cap mr-1"></i>Formations</a>
                    <a href="{{ route('membre.evenements.index') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:text-primary"><i class="fas fa-calendar mr-1"></i>Événements</a>
                    <a href="{{ route('membre.progression.index') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:text-primary"><i class="fas fa-arrow-up mr-1"></i>Progression</a>
                    @php $unreadNotifs = auth()->user()->notifications()->where('lue', false)->count(); @endphp
                    <a href="{{ route('membre.notifications.index') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:text-primary relative">
                        <i class="fas fa-bell mr-1"></i>
                        @if ($unreadNotifs > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">{{ $unreadNotifs }}</span>
                        @endif
                    </a>
                    <a href="{{ route('membre.compte.edit') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:text-primary"><i class="fas fa-user mr-1"></i>Mon Compte</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-700"><i class="fas fa-sign-out-alt mr-1"></i>Quitter</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    <main class="max-w-7xl mx-auto px-4 py-6">
        @yield('content')
    </main>
</body>
</html>
