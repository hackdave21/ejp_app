<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration | EJP - Église des Jeunes Prodiges</title>
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

        @font-face { font-family: 'FuturaStd'; src: url('{{ asset("fonts/FuturaStdLight.otf") }}') format('opentype'); font-weight: 300; font-style: normal; }
        @font-face { font-family: 'FuturaStd'; src: url('{{ asset("fonts/FuturaStdLightOblique.otf") }}') format('opentype'); font-weight: 300; font-style: italic; }
        @font-face { font-family: 'FuturaStd'; src: url('{{ asset("fonts/FuturaStdBook.otf") }}') format('opentype'); font-weight: 400; font-style: normal; }
        @font-face { font-family: 'FuturaStd'; src: url('{{ asset("fonts/FuturaStdBookOblique.otf") }}') format('opentype'); font-weight: 400; font-style: italic; }
        @font-face { font-family: 'FuturaStd'; src: url('{{ asset("fonts/FuturaStdMedium.otf") }}') format('opentype'); font-weight: 500; font-style: normal; }
        @font-face { font-family: 'FuturaStd'; src: url('{{ asset("fonts/FuturaStdMediumOblique.otf") }}') format('opentype'); font-weight: 500; font-style: italic; }
        @font-face { font-family: 'FuturaStd'; src: url('{{ asset("fonts/FuturaStdHeavy.otf") }}') format('opentype'); font-weight: 600; font-style: normal; }
        @font-face { font-family: 'FuturaStd'; src: url('{{ asset("fonts/FuturaStdHeavyOblique.otf") }}') format('opentype'); font-weight: 600; font-style: italic; }
        @font-face { font-family: 'FuturaStd'; src: url('{{ asset("fonts/FuturaStdBold.otf") }}') format('opentype'); font-weight: 700; font-style: normal; }
        @font-face { font-family: 'FuturaStd'; src: url('{{ asset("fonts/FuturaStdBoldOblique.otf") }}') format('opentype'); font-weight: 700; font-style: italic; }
        @font-face { font-family: 'FuturaStd'; src: url('{{ asset("fonts/FuturaStdExtraBold.otf") }}') format('opentype'); font-weight: 800; font-style: normal; }
        @font-face { font-family: 'FuturaStd'; src: url('{{ asset("fonts/FuturaStdExtraBoldOblique.otf") }}') format('opentype'); font-weight: 800; font-style: italic; }
        @font-face { font-family: 'FuturaStdCondensed'; src: url('{{ asset("fonts/FuturaStdCondensedLight.otf") }}') format('opentype'); font-weight: 300; font-style: normal; }
        @font-face { font-family: 'FuturaStdCondensed'; src: url('{{ asset("fonts/FuturaStdCondensedLightObl.otf") }}') format('opentype'); font-weight: 300; font-style: italic; }
        @font-face { font-family: 'FuturaStdCondensed'; src: url('{{ asset("fonts/FuturaStdCondensed.otf") }}') format('opentype'); font-weight: 400; font-style: normal; }
        @font-face { font-family: 'FuturaStdCondensed'; src: url('{{ asset("fonts/FuturaStdCondensedOblique.otf") }}') format('opentype'); font-weight: 400; font-style: italic; }
        @font-face { font-family: 'FuturaStdCondensed'; src: url('{{ asset("fonts/FuturaStdCondensedBold.otf") }}') format('opentype'); font-weight: 700; font-style: normal; }
        @font-face { font-family: 'FuturaStdCondensed'; src: url('{{ asset("fonts/FuturaStdCondensedBoldObl.otf") }}') format('opentype'); font-weight: 700; font-style: italic; }
        @font-face { font-family: 'FuturaStdCondensed'; src: url('{{ asset("fonts/FuturaStdCondensedExtraBd.otf") }}') format('opentype'); font-weight: 800; font-style: normal; }
        @font-face { font-family: 'FuturaStdCondensed'; src: url('{{ asset("fonts/FuturaStdCondExtraBoldObl.otf") }}') format('opentype'); font-weight: 800; font-style: italic; }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .font-light { font-weight: 300; }
        .font-book { font-weight: 400; }
        .font-heavy { font-weight: 600; }
        em, i { font-style: italic; }
        h1, h2, h3, h4, h5, h6 { font-weight: 700; letter-spacing: -0.02em; }
        .stat-value { font-weight: 800; letter-spacing: -0.03em; }
        .label { font-weight: 500; letter-spacing: 0.04em; text-transform: uppercase; font-size: 0.65rem; }
        .text-condensed { font-family: 'FuturaStdCondensed', sans-serif; letter-spacing: -0.01em; }
    </style>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: { primary: 'var(--color-primary)', 'primary-text': 'var(--color-primary-text)', accent: '#E74C3C', success: '#27AE60', danger: '#E74C3C', bg: 'var(--color-bg)', surface: 'var(--color-surface)', text: 'var(--color-text)', muted: 'var(--color-text-muted)', border: 'var(--color-border)', chef: '#115E59' },
                    fontFamily: { sans: ['FuturaStd', 'sans-serif'], serif: ['FuturaStd', 'sans-serif'], condensed: ['FuturaStdCondensed', 'sans-serif'] },
                    animation: {
                        'fade-up': 'fadeUp 0.6s ease-out forwards',
                        'shake': 'shake 0.5s ease-in-out',
                        'lock-close': 'lockClose 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards',
                    },
                    keyframes: {
                        fadeUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        shake: {
                            '0%, 100%': { transform: 'translateX(0)' },
                            '25%': { transform: 'translateX(-10px)' },
                            '75%': { transform: 'translateX(10px)' },
                        },
                        lockClose: {
                            '0%': { transform: 'translateY(-10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #050B14;
            min-height: 100vh;
            overflow: hidden;
        }

        #canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }

        .glass-card {
            background: rgba(10, 20, 35, 0.45);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(231, 76, 60, 0.15);
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.6);
        }

        .dark .glass-card {
            background: rgba(0, 0, 0, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .shimmer-btn {
            position: relative;
            overflow: hidden;
        }

        .shimmer-btn::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to bottom right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0) 40%,
                rgba(255, 255, 255, 0.3) 50%,
                rgba(255, 255, 255, 0) 60%,
                rgba(255, 255, 255, 0) 100%
            );
            transform: rotate(45deg);
            transition: all 0.3s;
            opacity: 0;
        }

        .shimmer-btn:hover::after {
            animation: shimmer 1.5s infinite;
            opacity: 1;
        }

        @keyframes shimmer {
            0% { transform: translateX(-150%) rotate(45deg); }
            100% { transform: translateX(150%) rotate(45deg); }
        }

        .bg-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M40 0l40 40-40 40L0 40z' fill='%23E74C3C' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        input:focus {
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.25);
            outline: none;
            border-color: #E74C3C !important;
        }

        .error-border {
            border-color: #E74C3C !important;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.2) !important;
        }

        .lockout-overlay {
            background: rgba(5, 11, 20, 0.95);
            backdrop-filter: blur(12px);
        }
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
            if (document.documentElement.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }
        }
    </script>
</head>

<body class="flex items-center justify-center p-4">
    <div class="bg-pattern"></div>
    <canvas id="canvas"></canvas>

    <div class="w-full max-w-5xl flex flex-col lg:flex-row glass-card rounded-3xl overflow-hidden relative z-10 animate-fade-up">

        <!-- Lockout Overlay -->
        <div id="lockout-view" class="lockout-overlay absolute inset-0 z-50 flex flex-col items-center justify-center text-center p-8 hidden">
            <div class="w-24 h-24 bg-danger/20 rounded-full flex items-center justify-center text-danger text-5xl mb-6 animate-lock-close border border-danger/30 shadow-lg shadow-danger/20">
                <i class="fas fa-user-lock"></i>
            </div>
            <h2 class="text-white text-3xl font-serif mb-3">Accès Verrouillé</h2>
            <p class="text-white/60 text-base max-w-sm mb-8">Trop de tentatives échouées par mesure de sécurité. Veuillez patienter avant de réessayer.</p>
            <div class="text-5xl font-mono text-accent font-bold bg-danger/10 px-8 py-4 rounded-2xl border border-danger/20 shadow-inner" id="timer">00:30</div>
        </div>

        <!-- Illustration / Administration Verse Pane -->
        <div class="hidden lg:flex lg:w-1/2 bg-slate-950 relative items-center justify-center p-12 overflow-hidden border-r border-danger/10">
            <div class="absolute inset-0 opacity-25">
                <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=1000&auto=format&fit=crop" alt="Admin Control Console" class="w-full h-full object-cover">
            </div>
            <div class="relative z-10 text-center px-4">
                <span class="bg-danger/20 text-danger border border-danger/30 px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest inline-flex items-center gap-2 mb-8">
                    <i class="fas fa-shield-alt"></i> Panel de Contrôle
                </span>
                <h2 class="text-white text-3xl font-serif mb-6 leading-relaxed">"Que tout se fasse avec bienséance et avec ordre."</h2>
                <p class="text-accent text-lg font-light tracking-wide">1 Corinthiens 14:40</p>
                <div class="mt-12 w-20 h-0.5 bg-danger/50 mx-auto rounded-full"></div>
            </div>
        </div>

        <!-- Admin Login Form Pane -->
        <div class="w-full lg:w-1/2 p-8 lg:p-16 flex flex-col justify-center bg-white/[0.02]">
            <div class="text-center mb-10">
                <div class="w-16 h-16 bg-black/60 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-danger/30 shadow-lg shadow-danger/20">
                    <span class="text-danger text-2xl font-serif font-bold">EJP</span>
                </div>
                <h1 class="text-white text-2xl font-serif mb-2">Espace Administration</h1>
                <p class="text-white/60 font-light text-sm">Veuillez renseigner vos accréditations de sécurité</p>
            </div>

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                @csrf

                @if ($errors->any())
                    <div class="bg-danger/10 border border-danger/20 text-danger text-sm font-bold text-center py-3 rounded-lg">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <!-- Identifiant -->
                <div>
                    <label class="block text-white/80 text-xs font-bold uppercase tracking-widest mb-2 ml-1" for="identifiant">Identifiant Admin</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-white/30 group-focus-within:text-accent transition-colors">
                            <i class="fas fa-shield-alt text-sm"></i>
                        </div>
                        <input type="text" id="identifiant" name="identifiant" value="{{ old('identifiant') }}" placeholder="admin@ejp.org"
                            class="w-full bg-white/5 border border-white/10 rounded-xl py-3.5 pl-11 pr-4 text-white placeholder-white/20 transition-all duration-300 @error('identifiant') error-border @enderror">
                    </div>
                    @error('identifiant')
                        <p class="text-danger text-xs mt-1 ml-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-white/80 text-xs font-bold uppercase tracking-widest mb-2 ml-1" for="password">Mot de passe sécurisé</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-white/30 group-focus-within:text-accent transition-colors">
                            <i class="fas fa-key text-sm"></i>
                        </div>
                        <input type="password" id="password" name="password" placeholder="••••••••"
                            class="w-full bg-white/5 border border-white/10 rounded-xl py-3.5 pl-11 pr-12 text-white placeholder-white/20 transition-all duration-300 @error('password') error-border @enderror">
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-white/30 hover:text-white transition-colors">
                            <i class="fas fa-eye text-sm" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-danger text-xs mt-1 ml-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Se souvenir de moi -->
                <div class="flex items-center gap-3 ml-1">
                    <input type="checkbox" id="reserver_connecte" name="reserver_connecte" value="1"
                        class="w-4 h-4 rounded border-white/20 bg-white/5 accent-danger">
                    <label for="reserver_connecte" class="text-white/60 text-xs font-medium">Se souvenir de moi</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitBtn" class="shimmer-btn w-full bg-danger hover:bg-danger/90 text-white font-bold py-4 rounded-xl transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-danger/25 flex items-center justify-center gap-2 mt-4">
                    <span id="btnText">Accéder au Dashboard</span>
                    <div id="btnLoader" class="hidden">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </button>
            </form>

            <div class="mt-12 text-center pt-6 border-t border-white/5">
                <p class="text-[9px] text-white/20 uppercase tracking-[0.25em] font-medium">Portail de supervision • Sécurité de Niveau 3</p>
            </div>
        </div>
    </div>

    <!-- Background Embers Particles -->
    <script>
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        let particles = [];

        function initCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }

        class Particle {
            constructor() {
                this.reset();
            }
            reset() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2 + 0.5;
                this.speedX = (Math.random() - 0.5) * 0.4;
                this.speedY = (Math.random() - 0.5) * 0.4;
                this.opacity = Math.random() * 0.4;
            }
            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
                if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;
            }
            draw() {
                ctx.fillStyle = `rgba(231, 76, 60, ${this.opacity})`;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        function createParticles() {
            particles = [];
            for (let i = 0; i < 40; i++) {
                particles.push(new Particle());
            }
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => {
                p.update();
                p.draw();
            });
            requestAnimationFrame(animate);
        }

        window.addEventListener('resize', initCanvas);
        initCanvas();
        createParticles();
        animate();

        document.addEventListener('mousemove', (e) => {
            const moveX = (e.clientX - window.innerWidth / 2) * 0.005;
            const moveY = (e.clientY - window.innerHeight / 2) * 0.005;
            document.querySelector('.bg-pattern').style.transform = `translate(${moveX}px, ${moveY}px)`;
        });
    </script>

    <!-- Form Interactive Script -->
    <script>
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnLoader = document.getElementById('btnLoader');
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const card = document.querySelector('.glass-card');
        const lockoutView = document.getElementById('lockout-view');
        const timerEl = document.getElementById('timer');

        let attempts = 0;
        let isLocked = false;

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    </script>

    <!-- Theme Toggle Floating Button -->
    <button onclick="toggleTheme()" class="fixed bottom-8 right-8 z-50 w-14 h-14 rounded-full bg-primary text-primary-text shadow-2xl flex items-center justify-center text-xl hover:scale-110 transition-transform border border-border">
        <i class="fas fa-moon dark:hidden"></i>
        <i class="fas fa-sun hidden dark:block"></i>
    </button>
</body>
</html>
