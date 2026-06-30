<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin') — EJP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --color-bg: #FFFFFF;
            --color-surface: #F9FAFB;
            --color-primary: #000000;
            --color-primary-text: #FFFFFF;
            --color-text: #111827;
            --color-text-muted: #6B7280;
            --color-border: #E5E7EB;
        }
        .dark {
            --color-bg: #000000;
            --color-surface: #000000;
            --color-primary: #FFFFFF;
            --color-primary-text: #000000;
            --color-text: #F9FAFB;
            --color-text-muted: #9CA3AF;
            --color-border: #1F2937;
        }
        @font-face { font-family: 'FuturaStd'; src: url('fonts/FuturaStdBook.otf') format('opentype'); font-weight: 400; font-style: normal; }
        @font-face { font-family: 'FuturaStd'; src: url('fonts/FuturaStdMedium.otf') format('opentype'); font-weight: 500; font-style: normal; }
        @font-face { font-family: 'FuturaStd'; src: url('fonts/FuturaStdHeavy.otf') format('opentype'); font-weight: 600; font-style: normal; }
        @font-face { font-family: 'FuturaStd'; src: url('fonts/FuturaStdBold.otf') format('opentype'); font-weight: 700; font-style: normal; }
        @font-face { font-family: 'FuturaStd'; src: url('fonts/FuturaStdExtraBold.otf') format('opentype'); font-weight: 800; font-style: normal; }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: { primary: 'var(--color-primary)', 'primary-text': 'var(--color-primary-text)', accent: '#F5A623', success: '#27AE60', danger: '#E74C3C', bg: 'var(--color-bg)', surface: 'var(--color-surface)', text: 'var(--color-text)', muted: 'var(--color-text-muted)', border: 'var(--color-border)', chef: '#115E59' },
                    fontFamily: { sans: ['FuturaStd', 'sans-serif'], serif: ['FuturaStd', 'sans-serif'] },
                    animation: { 'slide-in-right': 'slideInRight 0.5s ease-out forwards', 'fade-in-up': 'fadeInUp 0.5s ease-out forwards' },
                    keyframes: {
                        slideInRight: { '0%': { transform: 'translateX(30px)', opacity: '0' }, '100%': { transform: 'translateX(0)', opacity: '1' } },
                        fadeInUp: { '0%': { transform: 'translateY(20px)', opacity: '0' }, '100%': { transform: 'translateY(0)', opacity: '1' } }
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-link.active { background-color: rgba(245, 166, 35, 0.1); color: #F5A623; border-right: 4px solid #F5A623; }
        .sidebar-link:not(.active):hover { background-color: rgba(255, 255, 255, 0.05); }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
        .kpi-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .kpi-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); }
        .chart-bar { transition: height 1s ease-out; }
    </style>
    <script>
        function initTheme() {
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        initTheme();
        function toggleTheme() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        }
    </script>
</head>
<body class="bg-bg font-sans text-text overflow-hidden">

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar Admin -->
        <aside class="w-[280px] bg-surface border-r border-border flex flex-col h-full z-50 shrink-0">
            <div class="p-8 flex items-center gap-4">
                <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center border border-accent">
                    <span class="text-accent font-serif font-bold text-xl">EJP</span>
                </div>
                <div>
                    <h1 class="font-serif font-bold text-lg leading-tight">Admin Panel</h1>
                    <p class="text-[10px] text-muted uppercase tracking-widest">Back-Office</p>
                </div>
            </div>

            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto custom-scrollbar">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-muted' }}">
                    <i class="fas fa-chart-pie w-5"></i><span class="font-medium">Tableau de bord</span>
                </a>
                <a href="{{ route('admin.membres.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.membres.*') ? 'active' : 'text-muted' }}">
                    <i class="fas fa-users w-5"></i><span class="font-medium">Membres</span>
                </a>
                <a href="{{ route('admin.chefs.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.chefs.*') ? 'active' : 'text-muted' }}">
                    <i class="fas fa-user-tie w-5"></i><span class="font-medium">Chefs & Groupes</span>
                </a>
                <a href="{{ route('admin.formations.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.formations.*') ? 'active' : 'text-muted' }}">
                    <i class="fas fa-graduation-cap w-5"></i><span class="font-medium">Formations</span>
                </a>
                <a href="{{ route('admin.cultes.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.cultes.*') ? 'active' : 'text-muted' }}">
                    <i class="fas fa-church w-5"></i><span class="font-medium">Cultes</span>
                </a>
                <a href="{{ route('admin.reunions.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.reunions.*') ? 'active' : 'text-muted' }}">
                    <i class="fas fa-file-alt w-5"></i><span class="font-medium">PV Réunions</span>
                </a>
                <a href="{{ route('admin.evenements.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.evenements.*') ? 'active' : 'text-muted' }}">
                    <i class="fas fa-calendar-check w-5"></i><span class="font-medium">Événements</span>
                </a>
                <a href="{{ route('admin.communications.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.communications.*') ? 'active' : 'text-muted' }}">
                    <i class="fas fa-paper-plane w-5"></i><span class="font-medium">Communications</span>
                </a>
                <a href="{{ route('admin.suggestions.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.suggestions.*') ? 'active' : 'text-muted' }}">
                    <i class="fas fa-lightbulb w-5"></i><span class="font-medium">Suggestions</span>
                </a>
                <a href="{{ route('admin.demandes.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.demandes.*') ? 'active' : 'text-muted' }}">
                    <i class="fas fa-arrow-up w-5"></i><span class="font-medium">Progressions</span>
                </a>
                <a href="{{ route('admin.notifications.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.notifications.*') ? 'active' : 'text-muted' }}">
                    <i class="fas fa-bell w-5"></i>
                    <span class="font-medium">Notifications</span>
                    @php $unreadNotifs = auth()->user()->notifications()->where('lue', false)->count(); @endphp
                    @if ($unreadNotifs > 0)
                        <span class="ml-auto bg-danger text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $unreadNotifs }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.parametres.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.parametres.*') ? 'active' : 'text-muted' }}">
                    <i class="fas fa-sliders-h w-5"></i><span class="font-medium">Paramètres</span>
                </a>
            </nav>

            <div class="p-4 border-t border-border">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all text-danger hover:bg-danger/10 w-full text-left">
                        <i class="fas fa-sign-out-alt w-5"></i><span class="font-medium">Déconnexion</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden">

            <!-- Top Navbar -->
            <header class="h-20 bg-surface border-b border-border flex items-center justify-between px-8 shrink-0">
                <h2 class="text-xl font-bold text-primary">@yield('page_title', 'Vue d\'ensemble')</h2>

                <div class="flex items-center gap-6">
                    <!-- Search -->
                    <div class="relative hidden lg:block">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-muted"></i>
                        <input type="text" placeholder="Rechercher..." class="bg-bg border-none rounded-xl py-2.5 pl-12 pr-4 w-64 text-sm focus:ring-2 focus:ring-accent/20">
                    </div>

                    <!-- Notifs -->
                    <a href="{{ route('admin.notifications.index') }}" class="w-10 h-10 bg-bg rounded-xl flex items-center justify-center text-muted relative">
                        <i class="fas fa-bell"></i>
                        @if ($unreadNotifs > 0)
                            <span class="absolute top-2 right-2 w-2 h-2 bg-danger rounded-full border-2 border-white"></span>
                        @endif
                    </a>

                    <!-- Admin Profile -->
                    <div class="flex items-center gap-3 ml-4">
                        <div class="text-right">
                            <p class="text-sm font-bold text-primary">{{ auth()->user()->full_name }}</p>
                            <p class="text-[10px] text-danger font-bold uppercase tracking-widest">Super Utilisateur</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-accent font-bold">
                            {{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}{{ strtoupper(substr(auth()->user()->nom, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Page -->
            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar space-y-8">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Theme Toggle Floating Button -->
    <button onclick="toggleTheme()" class="fixed bottom-8 right-8 z-50 w-14 h-14 rounded-full bg-primary text-primary-text shadow-2xl flex items-center justify-center text-xl hover:scale-110 transition-transform border border-border">
        <i class="fas fa-moon dark:hidden"></i>
        <i class="fas fa-sun hidden dark:block"></i>
    </button>

    @stack('scripts')
</body>
</html>
