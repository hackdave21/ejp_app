<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tableau de bord') — EJP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --color-bg: #FFFFFF; --color-surface: #F9FAFB; --color-primary: #000000; --color-primary-text: #FFFFFF; --color-text: #111827; --color-text-muted: #6B7280; --color-border: #E5E7EB; }
        .dark { --color-bg: #000000; --color-surface: #000000; --color-primary: #FFFFFF; --color-primary-text: #000000; --color-text: #F9FAFB; --color-text-muted: #9CA3AF; --color-border: #1F2937; }
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
                    animation: { 'slide-in-left': 'slideInLeft 0.5s ease-out forwards', 'slide-in-right': 'slideInRight 0.5s ease-out forwards', 'fade-in-up': 'fadeInUp 0.5s ease-out forwards', 'pulse-soft': 'pulseSoft 2s infinite' },
                    keyframes: {
                        slideInLeft: { '0%': { transform: 'translateX(-30px)', opacity: '0' }, '100%': { transform: 'translateX(0)', opacity: '1' } },
                        slideInRight: { '0%': { transform: 'translateX(30px)', opacity: '0' }, '100%': { transform: 'translateX(0)', opacity: '1' } },
                        fadeInUp: { '0%': { transform: 'translateY(20px)', opacity: '0' }, '100%': { transform: 'translateY(0)', opacity: '1' } },
                        pulseSoft: { '0%, 100%': { transform: 'scale(1)', opacity: '1' }, '50%': { transform: 'scale(1.05)', opacity: '0.8' } }
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-link.active { background-color: #F5A623; color: #1E3A5F; }
        .sidebar-link.active::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background-color: #1E3A5F; }
        .sidebar-link:not(.active):hover { background-color: rgba(245, 166, 35, 0.1); color: #F5A623; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); }
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
<body class="bg-surface font-sans text-text overflow-hidden">

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-[260px] bg-black text-white transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="flex flex-col h-full">
                <div class="p-6 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center border border-accent">
                        <span class="text-accent font-serif font-bold">EJP</span>
                    </div>
                </div>

                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">
                    <a href="{{ route('membre.dashboard') }}" class="sidebar-link active relative flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('membre.dashboard') ? 'active' : 'text-muted' }}">
                        <i class="fas fa-home w-5"></i><span class="font-medium">Tableau de bord</span>
                    </a>
                    <a href="{{ route('membre.formations.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('membre.formations.*') ? 'active' : 'text-muted' }}">
                        <i class="fas fa-graduation-cap w-5"></i><span class="font-medium">Mes formations</span>
                    </a>
                    <a href="{{ route('membre.progression.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('membre.progression.*') ? 'active' : 'text-muted' }}">
                        <i class="fas fa-chart-line w-5"></i><span class="font-medium">Ma progression</span>
                    </a>
                    <a href="{{ route('membre.evenements.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('membre.evenements.*') ? 'active' : 'text-muted' }}">
                        <i class="fas fa-calendar-alt w-5"></i><span class="font-medium">Événements</span>
                    </a>
                    <a href="{{ route('membre.notifications.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('membre.notifications.*') ? 'active' : 'text-muted' }}">
                        <i class="fas fa-bell w-5"></i><span class="font-medium">Notifications</span>
                    </a>
                    <a href="{{ route('membre.compte.edit') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('membre.compte.*') ? 'active' : 'text-muted' }}">
                        <i class="fas fa-cog w-5"></i><span class="font-medium">Mon compte</span>
                    </a>
                </nav>

                <div class="p-4 border-t border-white/10 space-y-1">
                    <a href="{{ route('accueil') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all text-muted">
                        <i class="fas fa-globe w-5"></i><span class="font-medium">Retour à l'accueil</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all text-danger hover:bg-danger/10 w-full text-left">
                            <i class="fas fa-door-open w-5"></i><span class="font-medium">Déconnexion</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Overlay for mobile sidebar -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col lg:ml-[260px] h-screen overflow-hidden">

            <!-- Top Header -->
            <header class="sticky top-0 z-30 bg-surface border-b border-border px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button id="mobile-menu-btn" class="lg:hidden text-primary text-2xl">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2 class="text-xl font-bold text-primary hidden md:block">@yield('page_title', 'Tableau de bord')</h2>
                </div>

                <div class="flex items-center gap-6">
                    <!-- Notifications -->
                    <a href="{{ route('membre.notifications.index') }}" class="relative text-muted hover:text-primary transition-colors">
                        <i class="fas fa-bell text-xl"></i>
                        @php $unreadNotifs = auth()->user()->notifications()->where('lue', false)->count(); @endphp
                        @if ($unreadNotifs > 0)
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-danger text-white text-[10px] flex items-center justify-center rounded-full border-2 border-white">{{ $unreadNotifs }}</span>
                        @endif
                    </a>

                    <!-- Profile -->
                    <div class="flex items-center gap-3 border-l border-border pl-6">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-primary">{{ auth()->user()->full_name }}</p>
                            <p class="text-[10px] font-semibold text-accent uppercase tracking-wider">Statut : {{ ucfirst(str_replace('_', ' ', auth()->user()->statut ?? 'Membre')) }} @if(auth()->user()->statut === 'star')<i class="fas fa-star text-accent ml-0.5 animate-pulse-soft"></i>@endif</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-accent flex items-center justify-center text-primary font-bold shadow-sm">
                            {{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}{{ strtoupper(substr(auth()->user()->nom, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Body -->
            <div class="flex-1 overflow-y-auto p-6 custom-scrollbar space-y-8">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }

        mobileMenuBtn?.addEventListener('click', toggleSidebar);
        sidebarOverlay?.addEventListener('click', toggleSidebar);

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });
    </script>

    <!-- Theme Toggle Floating Button -->
    <button onclick="toggleTheme()" class="fixed bottom-8 right-8 z-50 w-14 h-14 rounded-full bg-primary text-primary-text shadow-2xl flex items-center justify-center text-xl hover:scale-110 transition-transform border border-border">
        <i class="fas fa-moon dark:hidden"></i>
        <i class="fas fa-sun hidden dark:block"></i>
    </button>

    @stack('scripts')
</body>
</html>
