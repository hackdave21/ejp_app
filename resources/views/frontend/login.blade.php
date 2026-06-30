<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — EJP</title>
    <meta name="description" content="Connectez-vous à votre espace membre EJP">

    <style>
        @font-face {
            font-family: 'FuturaStd';
            src: url('fonts/FuturaStdBook.otf') format('opentype');
            font-weight: 400;
            font-style: normal;
        }
        @font-face {
            font-family: 'FuturaStd';
            src: url('fonts/FuturaStdMedium.otf') format('opentype');
            font-weight: 500;
            font-style: normal;
        }
        @font-face {
            font-family: 'FuturaStd';
            src: url('fonts/FuturaStdHeavy.otf') format('opentype');
            font-weight: 600;
            font-style: normal;
        }
        @font-face {
            font-family: 'FuturaStd';
            src: url('fonts/FuturaStdBold.otf') format('opentype');
            font-weight: 700;
            font-style: normal;
        }
    </style>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#000000',
                        'primary-text': '#FFFFFF',
                        accent: '#F5A623',
                        success: '#27AE60',
                        danger: '#E74C3C',
                        bg: '#FFFFFF',
                        surface: '#F9FAFB',
                        text: '#111827',
                        muted: '#6B7280',
                        border: '#E5E7EB',
                        gold: '#F5A623',
                        'gold-light': '#FFD580',
                        marine: '#1E3A5F',
                    },
                    fontFamily: {
                        sans: ['FuturaStd', 'sans-serif'],
                        serif: ['FuturaStd', 'sans-serif'],
                        hero: ['FuturaStd', 'sans-serif'],
                        body: ['FuturaStd', 'sans-serif'],
                        mono: ['FuturaStd', 'sans-serif'],
                    },
                    animation: {
                        'fade-up': 'fadeUp 0.6s ease-out forwards',
                        'shake': 'shake 0.5s ease-in-out',
                    },
                    keyframes: {
                        fadeUp: { '0%': { opacity: '0', transform: 'translateY(20px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                        shake: { '0%, 100%': { transform: 'translateX(0)' }, '25%': { transform: 'translateX(-8px)' }, '75%': { transform: 'translateX(8px)' } },
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #060E1A;
            min-height: 100vh;
            overflow: hidden;
        }
        #canvas {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 0;
            pointer-events: none;
        }
        .glass-card {
            background: rgba(10, 20, 35, 0.45);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(245, 166, 35, 0.15);
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
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: linear-gradient(to bottom right, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0) 40%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0) 60%, rgba(255, 255, 255, 0) 100%);
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
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M40 0l40 40-40 40L0 40z' fill='%23F5A623' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }
        input:focus {
            box-shadow: 0 0 0 3px rgba(245, 166, 35, 0.25);
            outline: none;
            border-color: #F5A623 !important;
        }
        .error-border {
            border-color: #E74C3C !important;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.2) !important;
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

<body class="flex items-center justify-center p-4">
    <div class="bg-pattern"></div>
    <canvas id="canvas"></canvas>

    <div class="w-full max-w-5xl flex flex-col lg:flex-row glass-card rounded-3xl overflow-hidden relative z-10 animate-fade-up">

        <div class="hidden lg:flex lg:w-1/2 bg-slate-950 relative items-center justify-center p-12 overflow-hidden border-r border-gold/10">
            <div class="absolute inset-0 opacity-20">
                <img src="https://images.unsplash.com/photo-1545389336-cf090694435e?q=80&w=1000&auto=format&fit=crop" alt="EJP Community" class="w-full h-full object-cover">
            </div>
            <div class="relative z-10 text-center px-4">
                <div class="w-20 h-20 mx-auto mb-8 rounded-full bg-gold/20 border border-gold/30 flex items-center justify-center">
                    <i class="fas fa-dove text-4xl text-gold"></i>
                </div>
                <h2 class="text-white text-3xl font-hero mb-6 leading-relaxed">"Que personne ne méprise ta jeunesse ; mais sois un modèle pour les fidèles..."</h2>
                <p class="text-gold text-lg font-light tracking-wide">1 Timothée 4:12</p>
                <div class="mt-12 w-20 h-0.5 bg-gold/50 mx-auto rounded-full"></div>
                <p class="text-white/40 text-xs mt-8 font-body uppercase tracking-widest">Église des Jeunes Prodiges</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 p-8 lg:p-16 flex flex-col justify-center bg-white/[0.02]">
            <div class="text-center mb-10">
                <a href="{{ route('accueil') }}" class="inline-block w-16 h-16 bg-black/60 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-gold/30 shadow-lg shadow-gold/20 hover:scale-105 transition-transform">
                    <span class="text-gold text-2xl font-hero font-bold">EJP</span>
                </a>
                <h1 class="text-white text-2xl font-hero mb-2">Espace Membre</h1>
                <p class="text-white/60 font-light text-sm">Connectez-vous à votre compte</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                @if ($errors->any())
                <div class="p-4 bg-danger/10 border border-danger/30 rounded-xl flex items-start gap-3 animate-shake">
                    <i class="fas fa-exclamation-circle text-danger mt-0.5"></i>
                    <p class="text-danger text-sm">{{ $errors->first('identifiant') }}</p>
                </div>
                @endif

                <div>
                    <label class="block text-white/80 text-xs font-bold uppercase tracking-widest mb-2 ml-1" for="identifiant">Email ou Téléphone</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-white/30 group-focus-within:text-gold transition-colors">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <input type="text" id="identifiant" name="identifiant" value="{{ old('identifiant') }}" required placeholder="identifiant@email.com ou +225"
                            class="w-full bg-white/5 border border-white/10 rounded-xl py-3.5 pl-11 pr-4 text-white placeholder-white/20 transition-all duration-300 @error('identifiant') error-border @enderror">
                    </div>
                </div>

                <div>
                    <label class="block text-white/80 text-xs font-bold uppercase tracking-widest mb-2 ml-1" for="password">Mot de passe</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-white/30 group-focus-within:text-gold transition-colors">
                            <i class="fas fa-lock text-sm"></i>
                        </div>
                        <input type="password" id="password" name="password" required placeholder="••••••••"
                            class="w-full bg-white/5 border border-white/10 rounded-xl py-3.5 pl-11 pr-12 text-white placeholder-white/20 transition-all duration-300">
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-white/30 hover:text-white transition-colors">
                            <i class="fas fa-eye text-sm" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <label class="flex items-center gap-3 cursor-pointer group">
                    <div class="relative">
                        <input type="checkbox" name="reserver_connecte" value="1" class="sr-only peer">
                        <div class="w-5 h-5 rounded-md border border-white/20 bg-white/5 peer-checked:bg-gold peer-checked:border-gold transition-all group-hover:border-white/40 flex items-center justify-center">
                            <i class="fas fa-check text-[10px] text-marine opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                        </div>
                    </div>
                    <span class="text-white/60 text-sm group-hover:text-white/80 transition-colors">Rester connecté</span>
                </label>

                <button type="submit" id="submitBtn" class="shimmer-btn w-full bg-gold hover:bg-gold/90 text-marine font-bold py-4 rounded-xl transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-gold/25 flex items-center justify-center gap-2 mt-2">
                    <span id="btnText">Se connecter</span>
                    <div id="btnLoader" class="hidden">
                        <svg class="animate-spin h-5 w-5 text-marine" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </button>
            </form>

            <div class="mt-10 text-center pt-6 border-t border-white/5">
                <div class="flex items-center justify-center gap-2 text-white/30 text-xs mb-2">
                    <i class="fas fa-chevron-left"></i>
                    <a href="{{ route('accueil') }}" class="text-white/40 hover:text-gold transition-colors">Retour à l'accueil</a>
                </div>
                <p class="text-[9px] text-white/15 uppercase tracking-[0.25em] font-medium">Portail Membres • Église des Jeunes Prodiges</p>
            </div>
        </div>
    </div>

    <button onclick="toggleTheme()" class="fixed bottom-8 right-8 z-50 w-14 h-14 rounded-full bg-primary text-primary-text shadow-2xl flex items-center justify-center text-xl hover:scale-110 transition-transform border border-white/10">
        <i class="fas fa-moon dark:hidden"></i>
        <i class="fas fa-sun hidden dark:block"></i>
    </button>

    <script>
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        let particles = [];

        function initCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }

        class Particle {
            constructor() { this.reset(); }
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
                ctx.fillStyle = `rgba(245, 166, 35, ${this.opacity})`;
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
            particles.forEach(p => { p.update(); p.draw(); });
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

        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });

        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnLoader = document.getElementById('btnLoader');

        document.querySelector('form').addEventListener('submit', (e) => {
            submitBtn.disabled = true;
            btnText.classList.add('hidden');
            btnLoader.classList.remove('hidden');
        });
    </script>
</body>
</html>