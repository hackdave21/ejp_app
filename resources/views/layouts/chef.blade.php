<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tableau de Bord Chef') — EJP</title>
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
                    animation: { 'fade-in-up': 'fadeInUp 0.5s ease-out forwards' },
                    keyframes: {
                        fadeInUp: { '0%': { transform: 'translateY(15px)', opacity: '0' }, '100%': { transform: 'translateY(0)', opacity: '1' } }
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-link.active { background-color: rgba(17, 94, 89, 0.1); color: #115E59; border-right: 4px solid #115E59; }
        .sidebar-link:not(.active):hover { background-color: rgba(0, 0, 0, 0.02); }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .kpi-icon-blue  { background-color: rgba(59,130,246,0.12); color: #3B82F6; }
        .kpi-icon-green { background-color: rgba(17,94,89,0.15); color: #115E59; }
        .kpi-icon-red   { background-color: rgba(231,76,60,0.12); color: #E74C3C; }
        .alert-amber { background-color: rgba(245,166,35,0.1); border-color: rgba(245,166,35,0.25); }
        .alert-blue  { background-color: rgba(59,130,246,0.1); border-color: rgba(59,130,246,0.25); }
        .dark .timeline-dot { border-color: #000 !important; }
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

        <!-- Sidebar Chef -->
        <aside class="w-[280px] bg-surface border-r border-border flex flex-col h-full z-50 shrink-0">
            <div class="p-8 flex items-center gap-4">
                <div class="w-12 h-12 bg-chef rounded-xl flex items-center justify-center border border-white shadow-md">
                    <span class="text-white font-serif font-bold text-xl">EJP</span>
                </div>
                <div>
                    <h1 class="font-serif font-bold text-lg leading-tight text-primary">Portail Chef</h1>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-chef">Responsable Famille</p>
                </div>
            </div>

            <div class="px-8 pb-4">
                <div class="flex items-center gap-3 bg-surface p-3 rounded-xl border border-border">
                    <div class="w-10 h-10 rounded-full bg-primary text-primary-text flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}{{ strtoupper(substr(auth()->user()->nom, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold text-sm text-primary">{{ auth()->user()->full_name }}</p>
                        <p class="text-xs text-muted">{{ auth()->user()->chef?->groupes->first()?->nom ?? 'Famille' }}</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto custom-scrollbar">
                <a href="{{ route('chef.dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('chef.dashboard') ? 'active' : 'text-muted' }}">
                    <i class="fas fa-chart-pie w-5"></i><span class="font-medium">Vue d'ensemble</span>
                </a>
                <a href="{{ route('chef.membres.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('chef.membres.*') ? 'active' : 'text-muted' }}">
                    <i class="fas fa-users w-5"></i><span class="font-medium">Ma Famille</span>
                </a>
                <a href="{{ route('chef.demandes.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('chef.demandes.*') ? 'active' : 'text-muted' }}">
                    <i class="fas fa-file-signature w-5"></i>
                    <span class="font-medium">Demandes</span>
                    @php $demandesCount = \App\Models\DemandeProgression::whereIn('membre_id', auth()->user()->chef?->groupes->flatMap->membres->pluck('id') ?? collect())->where('statut', 'en_attente')->count(); @endphp
                    @if ($demandesCount > 0)
                        <span class="ml-auto bg-danger text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $demandesCount }}</span>
                    @endif
                </a>
                <a href="{{ route('chef.reunions.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('chef.reunions.*') ? 'active' : 'text-muted' }}">
                    <i class="fas fa-file-alt w-5"></i><span class="font-medium">PV Réunions</span>
                </a>
            </nav>

            <div class="p-4 border-t border-border">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-lg text-danger hover:bg-danger/10 transition-colors font-medium w-full text-left">
                        <i class="fas fa-sign-out-alt w-5"></i> Déconnexion
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden">

            <!-- Top Navbar -->
            <header class="h-20 bg-surface/80 backdrop-blur-md border-b border-border flex items-center justify-between px-8 shrink-0">
                <h2 class="text-xl font-bold text-primary font-serif"><i class="fas fa-hand-sparkles text-accent"></i> @yield('greeting', 'Bonjour, ' . auth()->user()->prenom)</h2>
                <div class="flex items-center gap-4">
                    <a href="{{ route('chef.notifications.index') }}" class="w-10 h-10 rounded-full bg-surface border border-border text-muted hover:text-primary transition-colors flex items-center justify-center relative shadow-sm">
                        <i class="fas fa-bell"></i>
                        @php $unreadNotifs = auth()->user()->notifications()->where('lue', false)->count(); @endphp
                        @if ($unreadNotifs > 0)
                            <span class="absolute top-2 right-2 w-2 h-2 bg-danger rounded-full border border-white"></span>
                        @endif
                    </a>
                </div>
            </header>

            <!-- Scrollable Body -->
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
