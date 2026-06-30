<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Chef | EJP</title>
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

        @font-face {
            font-family: 'FuturaStd';
            src: url('{{ asset("ejptemplate/chef/fonts/FuturaStdBook.otf") }}') format('opentype');
            font-weight: 400;
            font-style: normal;
        }
        @font-face {
            font-family: 'FuturaStd';
            src: url('{{ asset("ejptemplate/chef/fonts/FuturaStdMedium.otf") }}') format('opentype');
            font-weight: 500;
            font-style: normal;
        }
        @font-face {
            font-family: 'FuturaStd';
            src: url('{{ asset("ejptemplate/chef/fonts/FuturaStdHeavy.otf") }}') format('opentype');
            font-weight: 600;
            font-style: normal;
        }
        @font-face {
            font-family: 'FuturaStd';
            src: url('{{ asset("ejptemplate/chef/fonts/FuturaStdBold.otf") }}') format('opentype');
            font-weight: 700;
            font-style: normal;
        }
        @font-face {
            font-family: 'FuturaStd';
            src: url('{{ asset("ejptemplate/chef/fonts/FuturaStdExtraBold.otf") }}') format('opentype');
            font-weight: 800;
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
                    colors: { primary: 'var(--color-primary)', 'primary-text': 'var(--color-primary-text)', accent: '#F5A623', success: '#27AE60', danger: '#E74C3C', bg: 'var(--color-bg)', surface: 'var(--color-surface)', text: 'var(--color-text)', muted: 'var(--color-text-muted)', border: 'var(--color-border)', chef: '#115E59' },
                    fontFamily: {
                        sans: ['FuturaStd', 'sans-serif'],
                        serif: ['FuturaStd', 'sans-serif'],
                    },
                    animation: {
                        'slide-up': 'slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                        'shake': 'shake 0.5s cubic-bezier(.36,.07,.19,.97) both',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        slideUp: {
                            '0%': { transform: 'translateY(40px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        shake: {
                            '10%, 90%': { transform: 'translate3d(-1px, 0, 0)' },
                            '20%, 80%': { transform: 'translate3d(2px, 0, 0)' },
                            '30%, 50%, 70%': { transform: 'translate3d(-4px, 0, 0)' },
                            '40%, 60%': { transform: 'translate3d(4px, 0, 0)' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .input-glow:focus-within {
            box-shadow: 0 0 15px rgba(245, 166, 35, 0.3);
            border-color: rgba(245, 166, 35, 0.5);
        }

        .btn-shimmer {
            position: relative;
            overflow: hidden;
        }
        .btn-shimmer::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%);
            transform: rotate(30deg) translateX(-100%);
            transition: transform 0.8s ease-in-out;
        }
        .btn-shimmer:hover::after {
            transform: rotate(30deg) translateX(100%);
        }

        .bg-pattern {
            background-image: radial-gradient(#F5A623 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: 0.03;
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

<body class="min-h-screen flex font-sans text-white relative overflow-hidden bg-gradient-to-br from-chef to-dark">

    <!-- Background Elements -->
    <div class="absolute inset-0 bg-pattern z-0"></div>
    <div class="absolute top-[-10%] right-[-5%] w-[40vw] h-[40vw] rounded-full bg-accent opacity-10 blur-3xl mix-blend-screen animate-float" style="animation-delay: -2s;"></div>
    <div class="absolute bottom-[-10%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-primary opacity-20 blur-3xl mix-blend-screen animate-float"></div>

    <!-- Main Container -->
    <div class="w-full flex items-center justify-center p-6 z-10">

        <div id="login-card" class="w-full max-w-md glass-card rounded-3xl p-10 opacity-0 animate-slide-up shadow-2xl shadow-black/50">

            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-primary rounded-full mx-auto mb-4 flex items-center justify-center border-2 border-accent shadow-lg shadow-accent/20">
                    <span class="text-accent font-serif font-bold text-3xl">EJP</span>
                </div>
                <div class="inline-block px-3 py-1 bg-surface/10 rounded-full mb-3 border border-white/20">
                    <span class="text-xs font-bold uppercase tracking-widest text-emerald-400 flex items-center gap-2"><i class="fas fa-shield-alt"></i> Portail Responsables</span>
                </div>
                <h2 class="font-serif text-3xl font-bold mb-2">Bon retour, Chef</h2>
                <p class="text-white/60 text-sm">Gérez votre Famille de Disciples.</p>
            </div>

            <form method="POST" action="{{ route('chef.login') }}" class="space-y-6">
                @csrf

                @if ($errors->any())
                    <div id="error-msg" class="text-danger text-xs font-bold text-center bg-danger/10 py-2 rounded-lg border border-danger/20">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <!-- Identifiant -->
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-widest text-white/70 ml-1">Identifiant Chef</label>
                    <div class="relative input-glow rounded-xl transition-all duration-300 border border-white/10 bg-surface/5 @error('identifiant') border-danger/50 @enderror">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-white/40">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <input type="text" id="identifiant" name="identifiant" value="{{ old('identifiant') }}" placeholder="chef@ejp.org"
                            class="w-full pl-11 pr-4 py-3.5 bg-transparent outline-none text-white placeholder-white/30 text-sm">
                    </div>
                    @error('identifiant')
                        <p class="text-danger text-xs mt-1 ml-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between ml-1">
                        <label class="text-xs font-bold uppercase tracking-widest text-white/70">Mot de passe</label>
                        <a href="#" class="text-xs text-accent hover:text-white transition-colors">Oublié ?</a>
                    </div>
                    <div class="relative input-glow rounded-xl transition-all duration-300 border border-white/10 bg-surface/5 @error('password') border-danger/50 @enderror">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-white/40">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" id="password" name="password" class="w-full pl-11 pr-12 py-3.5 bg-transparent outline-none text-white placeholder-white/30 text-sm" placeholder="••••••••">
                        <button type="button" id="toggle-pwd" class="absolute inset-y-0 right-0 pr-4 flex items-center text-white/40 hover:text-white transition-colors">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-danger text-xs mt-1 ml-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Se souvenir de moi -->
                <div class="flex items-center gap-3 ml-1">
                    <input type="checkbox" id="reserver_connecte" name="reserver_connecte" value="1"
                        class="w-4 h-4 rounded border-white/20 bg-white/5 accent-accent">
                    <label for="reserver_connecte" class="text-white/60 text-xs font-medium">Se souvenir de moi</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submit-btn" class="w-full bg-accent hover:bg-[#E0961B] text-primary font-bold py-4 rounded-xl transition-all transform hover:scale-[1.02] shadow-lg shadow-accent/20 btn-shimmer flex justify-center items-center">
                    <span id="btn-text">Accéder à mon espace</span>
                    <i id="btn-spinner" class="fas fa-circle-notch fa-spin hidden"></i>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-white/10 text-center">
                <p class="text-xs text-white/40">Réservé aux Chefs de Groupe EJP. <br>En cas de problème, contactez l'administration.</p>
            </div>

        </div>
    </div>

    <script>
        const togglePwd = document.getElementById('toggle-pwd');
        const pwdInput = document.getElementById('password');

        togglePwd.addEventListener('click', () => {
            const type = pwdInput.getAttribute('type') === 'password' ? 'text' : 'password';
            pwdInput.setAttribute('type', type);
            togglePwd.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    </script>

    <!-- Theme Toggle Floating Button -->
    <button onclick="toggleTheme()" class="fixed bottom-8 right-8 z-50 w-14 h-14 rounded-full bg-primary text-primary-text shadow-2xl flex items-center justify-center text-xl hover:scale-110 transition-transform border border-border">
        <i class="fas fa-moon dark:hidden"></i>
        <i class="fas fa-sun hidden dark:block"></i>
    </button>
</body>
</html>
