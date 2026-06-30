<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EJP — Église des Jeunes Prodiges</title>
    <meta name="description" content="L'Église des Jeunes Prodiges (EJP) est une famille dynamique qui forme, élève et envoie la jeunesse au service de Dieu.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        @font-face {
            font-family: 'FuturaStd';
            src: url('fonts/FuturaStdBook.otf') format('opentype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'FuturaStd';
            src: url('fonts/FuturaStdHeavy.otf') format('opentype');
            font-weight: bold;
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
                        primary: 'var(--color-primary)',
                        'primary-text': 'var(--color-primary-text)',
                        accent: '#F5A623',
                        success: '#27AE60',
                        danger: '#E74C3C',
                        bg: 'var(--color-bg)',
                        surface: 'var(--color-surface)',
                        text: 'var(--color-text)',
                        muted: 'var(--color-text-muted)',
                        border: 'var(--color-border)',
                        gold: '#F5A623',
                        'gold-light': '#FFD580',
                        marine: '#1E3A5F',
                    },
                    fontFamily: {
                        sans: ['FuturaStd', 'sans-serif'],
                        serif: ['FuturaStd', 'sans-serif'],
                        hero: ['FuturaStd', 'sans-serif'],
                        title: ['FuturaStd', 'sans-serif'],
                        body: ['FuturaStd', 'sans-serif'],
                        mono: ['FuturaStd', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        :root {
            --color-bg: #FFFFFF;
            --color-surface: #F9FAFB;
            --color-primary: #000000;
            --color-primary-text: #FFFFFF;
            --color-text: #111827;
            --color-text-muted: #6B7280;
            --color-border: #E5E7EB;
            --ejp-marine: #1E3A5F;
            --ejp-gold: #F5A623;
            --ejp-gold-light: #FFD580;
            --ejp-dark: #0A1628;
            --ejp-white: #FAFAF8;
            --ejp-gray: #8A9BB5;
            --ejp-green: #27AE60;
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

        body {
            background-color: var(--color-bg);
            color: var(--color-text);
            font-family: 'FuturaStd', sans-serif;
            overflow-x: hidden;
            position: relative;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            opacity: 0.03;
            pointer-events: none;
            z-index: 9999;
        }

        #navbar {
            transition: background-color 0.4s ease, border-bottom 0.4s ease, transform 0.6s ease-out;
            transform: translateY(-100%);
            opacity: 0;
            animation: navSlideDown 0.6s ease-out 0.2s forwards;
        }
        #navbar.scrolled {
            background-color: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        }
        .dark #navbar.scrolled {
            background-color: rgba(0, 0, 0, 0.6);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
        }
        #navbar.scrolled .nav-link { color: var(--color-text) !important; }
        #navbar.scrolled .nav-link:hover { color: var(--ejp-gold) !important; }
        #navbar.scrolled .nav-title { color: var(--color-text) !important; }
        #navbar.scrolled .hamburger-line { background-color: var(--color-text) !important; }
        @keyframes navSlideDown { to { transform: translateY(0); opacity: 1; } }

        .hamburger-line { transition: transform 0.3s, opacity 0.3s; }
        .menu-open .line-1 { transform: translateY(8px) rotate(45deg); }
        .menu-open .line-2 { opacity: 0; }
        .menu-open .line-3 { transform: translateY(-8px) rotate(-45deg); }
        #mobile-menu { transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1); }

        .hero-slide {
            position: absolute; inset: 0;
            opacity: 0; transition: opacity 1.2s ease-in-out;
            z-index: 0;
        }
        .hero-slide.active { opacity: 1; z-index: 1; animation: kenBurns 5s linear forwards; }
        @keyframes kenBurns {
            0% { transform: scale(1); }
            100% { transform: scale(1.05); }
        }
        .hero-overlay {
            position: absolute; inset: 0; z-index: 2;
            background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.6) 70%, #060E1A 100%);
        }
        .slide-indicator { width: 30px; height: 3px; background: rgba(255,255,255,0.3); transition: all 0.3s; cursor: pointer; }
        .slide-indicator.active { background: var(--ejp-gold); width: 50px; }
        .reveal { opacity: 0; transform: translateY(30px); transition: opacity 700ms ease, transform 700ms ease; }
        .reveal.active { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: 150ms; }
        .reveal-delay-2 { transition-delay: 300ms; }
        .reveal-delay-3 { transition-delay: 450ms; }
        .tilt-card { transform-style: preserve-3d; transition: transform 0.1s ease, box-shadow 0.3s ease, border-color 0.3s ease, background-color 0.3s ease; }
        .tilt-card-inner { transform: translateZ(30px); }
        .word { display: inline-block; opacity: 0; transform: translateY(30px); }
        .word.animate { animation: slideUpWord 0.6s ease-out forwards; }
        @keyframes slideUpWord { to { opacity: 1; transform: translateY(0); } }
        .marquee-container { overflow: hidden; white-space: nowrap; width: 100%; }
        .marquee-content { display: inline-block; animation: marquee 40s linear infinite; }
        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        #sphere-container { perspective: 1000px; transform-style: preserve-3d; }
        .particle { position: absolute; width: 4px; height: 4px; background: var(--ejp-gold); border-radius: 50%; transform-style: preserve-3d; box-shadow: 0 0 10px var(--ejp-gold-light); }

        @keyframes floatDove {
            0%, 100% { transform: translateX(-50%) translateY(0px) rotate(-3deg); }
            50% { transform: translateX(-50%) translateY(-15px) rotate(3deg); }
        }
        @keyframes flickerFlame {
            0% { transform: scaleY(1) scaleX(1) rotate(-2deg); opacity: 0.8; }
            50% { transform: scaleY(1.15) scaleX(1.05) rotate(2deg); opacity: 1; filter: brightness(1.3); }
            100% { transform: scaleY(0.95) scaleX(0.95) rotate(-1deg); opacity: 0.9; }
        }
    </style>

    <script>
        function toggleTheme() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        }
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body data-scroll="0">

    <nav id="navbar" class="fixed top-0 w-full z-50 px-6 py-4 border-b border-transparent">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="#" class="flex items-center gap-3 group">
                <span class="font-hero text-gold text-2xl font-bold group-hover:text-gold-light transition-colors">EJP</span>
                <span class="nav-title font-body text-white text-sm tracking-wide hidden sm:block transition-colors">Église des Jeunes Prodiges</span>
            </a>

            <div class="hidden md:flex items-center gap-8">
                <a href="#hero" class="nav-link text-white text-sm font-body hover:text-gold transition-colors">Accueil</a>
                <a href="#about" class="nav-link text-white text-sm font-body hover:text-gold transition-colors">À propos</a>
                <a href="#parcours" class="nav-link text-white text-sm font-body hover:text-gold transition-colors">Parcours</a>
                <a href="#evenements" class="nav-link text-white text-sm font-body hover:text-gold transition-colors">Événements</a>
                <a href="#formations" class="nav-link text-white text-sm font-body hover:text-gold transition-colors">Formations</a>

                <div class="flex items-center gap-2 text-xs font-mono tracking-widest ml-4 border-l border-black/20 dark:border-white/20 pl-6">
                    <button class="lang-switcher text-gold font-bold transition-colors" data-lang="fr">FR</button>
                    <span class="text-black/30 dark:text-white/30">|</span>
                    <button class="lang-switcher text-black dark:text-white opacity-70 hover:opacity-100 transition-colors" data-lang="en">EN</button>
                </div>
            </div>

            <div class="hidden md:block">
                <a href="{{ route('login') }}" class="inline-block px-6 py-2 border border-gold text-gold font-body text-sm rounded-full hover:bg-gold hover:text-marine transition-all duration-250 hover:scale-105">
                    Se connecter
                </a>
            </div>

            <button id="burger-btn" class="md:hidden w-8 h-8 flex flex-col justify-center gap-1.5 z-50 relative">
                <span class="line-1 w-full h-0.5 bg-white hamburger-line"></span>
                <span class="line-2 w-full h-0.5 bg-white hamburger-line"></span>
                <span class="line-3 w-full h-0.5 bg-white hamburger-line"></span>
            </button>
        </div>
    </nav>

    <div id="mobile-menu" class="fixed inset-0 bg-bg z-40 flex flex-col items-center justify-center -translate-y-full">
        <div class="flex flex-col items-center gap-6 text-2xl font-title">
            <a href="#hero" class="text-text hover:text-gold transition-colors mobile-link">Accueil</a>
            <a href="#about" class="text-text hover:text-gold transition-colors mobile-link">À propos</a>
            <a href="#parcours" class="text-text hover:text-gold transition-colors mobile-link">Le Parcours</a>
            <a href="#evenements" class="text-text hover:text-gold transition-colors mobile-link">Événements</a>
            <a href="#formations" class="text-text hover:text-gold transition-colors mobile-link">Formations</a>

            <div class="flex items-center gap-4 text-base font-mono tracking-widest mt-4">
                <button class="lang-switcher text-gold font-bold transition-colors" data-lang="fr">FR</button>
                <span class="text-text/30">|</span>
                <button class="lang-switcher text-black dark:text-white opacity-70 hover:opacity-100 transition-colors" data-lang="en">EN</button>
            </div>

            <a href="{{ route('login') }}" class="mt-4 w-64 text-center px-8 py-4 bg-gold text-marine font-body text-lg rounded-full font-bold">Se connecter</a>
        </div>
    </div>

    <main>
        <section id="hero" class="relative h-screen min-h-[600px] flex items-center justify-center overflow-hidden">
            <div id="slide-1" class="hero-slide active" style="background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(30,58,95,0.8) 100%), url('assets/hero1.jpg') center/cover;"></div>
            <div id="slide-2" class="hero-slide" style="background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(10,40,24,0.8) 100%), url('assets/hero2.jpg') center/cover;"></div>
            <div id="slide-3" class="hero-slide" style="background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(45,27,0,0.8) 100%), url('assets/hero3.jpg') center/cover;"></div>
            <div id="slide-4" class="hero-slide" style="background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(45,27,0,0.8) 100%), url('assets/hero4.jpg') center/cover;"></div>
            <div id="slide-5" class="hero-slide" style="background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(15,27,45,0.8) 100%), url('assets/hero5.jpg') center/cover;"></div>

            <div class="hero-overlay" id="parallax-overlay"></div>

            <div class="relative z-10 w-full max-w-7xl mx-auto px-6 mt-16 flex flex-col md:flex-row items-center justify-between">
                <div class="w-full md:w-2/3 flex flex-col items-center md:items-start text-center md:text-left">
                    <div class="inline-block px-5 py-2 mb-8 rounded-full border border-gold/30 bg-gold/10 text-gold font-body text-xs md:text-sm italic animate-[slideUpWord_0.6s_ease-out_0.3s_both] max-w-2xl text-center md:text-left shadow-[0_0_15px_rgba(245,166,35,0.15)]">
                        « Que personne ne méprise ta jeunesse ; mais sois un modèle pour les fidèles... » <span class="font-bold not-italic ml-2">— 1 Timothée 4:12</span>
                    </div>

                    <h1 class="font-hero text-6xl md:text-7xl lg:text-[80px] leading-tight mb-8">
                        <span class="block text-white font-light text-5xl md:text-6xl mb-4" id="hero-title-1">Bienvenue dans votre</span>
                        <span class="block text-gold font-bold italic mt-2" id="hero-title-2">Famille Spirituelle</span>
                    </h1>

                    <p id="hero-subtitle" class="font-body text-white/80 text-lg md:text-xl font-medium max-w-[560px] mb-10 transition-opacity duration-300">
                        Une communauté soudée par la foi
                    </p>

                    <div class="flex flex-col sm:flex-row items-center gap-4 animate-[slideUpWord_0.6s_ease-out_0.9s_both]">
                        <a href="#about" class="px-8 py-4 bg-gold text-dark rounded-full font-body font-bold shadow-[0_0_30px_rgba(245,166,35,0.4)] hover:scale-105 hover:shadow-[0_0_40px_rgba(245,166,35,0.6)] transition-all duration-300">
                            Rejoindre la communauté →
                        </a>
                        <a href="{{ route('login') }}" class="px-8 py-4 border border-white text-white rounded-full font-body hover:bg-white/10 transition-colors duration-200">
                            Se connecter à mon espace
                        </a>
                    </div>
                </div>

                <div class="hidden lg:block w-1/3 relative h-[400px]">
                    <div class="relative w-full max-w-[300px] h-full mx-auto overflow-visible flex items-center justify-center">
                        <div class="absolute w-64 h-64 bg-gold/10 blur-[60px] rounded-full animate-pulse"></div>
                        <div class="absolute top-[80px] w-[8px] h-[240px] bg-gradient-to-b from-white/90 via-white to-gold/30 rounded-full shadow-[0_0_20px_rgba(255,255,255,0.8)]"></div>
                        <div class="absolute top-[140px] w-[140px] h-[8px] bg-gradient-to-r from-gold/30 via-white to-gold/30 rounded-full shadow-[0_0_20px_rgba(255,255,255,0.8)]"></div>
                        <i class="fas fa-dove absolute left-1/2 top-[80px] text-white text-[70px] z-20 drop-shadow-[0_0_25px_rgba(255,255,255,1)]" style="animation: floatDove 4s ease-in-out infinite;"></i>
                        <div class="absolute left-1/2 -translate-x-1/2 bottom-[40px] flex justify-center items-end z-30 w-[100px] h-[100px]">
                            <i class="fas fa-fire absolute text-red-600/80 text-[80px] drop-shadow-[0_0_30px_rgba(239,68,68,0.8)] origin-bottom" style="animation: flickerFlame 0.4s ease-in-out infinite alternate;"></i>
                            <i class="fas fa-fire absolute text-orange-500/90 text-[60px] drop-shadow-[0_0_20px_rgba(249,115,22,0.8)] origin-bottom mb-2" style="animation: flickerFlame 0.3s ease-in-out infinite alternate-reverse;"></i>
                            <i class="fas fa-fire absolute text-yellow-400 text-[40px] drop-shadow-[0_0_15px_rgba(253,224,71,0.8)] origin-bottom mb-4" style="animation: flickerFlame 0.2s ease-in-out infinite alternate;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex gap-3">
                <div class="slide-indicator active" onclick="goToSlide(0)"></div>
                <div class="slide-indicator" onclick="goToSlide(1)"></div>
                <div class="slide-indicator" onclick="goToSlide(2)"></div>
                <div class="slide-indicator" onclick="goToSlide(3)"></div>
                <div class="slide-indicator" onclick="goToSlide(4)"></div>
            </div>
        </section>

        <section id="about" class="py-24 bg-bg relative">
            <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
                <div class="reveal">
                    <span class="font-mono text-gold text-sm tracking-[3px] uppercase block mb-4">Notre Identité</span>
                    <h2 class="font-title text-text text-4xl md:text-[42px] leading-[1.2] mb-6">Une église animée par la vision d'ANAGKAZO</h2>
                    <p class="font-body text-text/75 text-base md:text-lg leading-[1.8] font-light mb-10">
                        Ayant une vision claire provenant de l'ordre suprême de Matthieu 28:19 — <em>« Allez, faites de toutes les nations des disciples »</em> — l'Église des Jeunes Prodiges a pour vision d'aller chercher activement les âmes, en particulier la jeunesse, pour les accueillir, les former et faire d'eux des disciples accomplis.
                    </p>

                    <div class="space-y-8">
                        <div class="flex gap-6 items-start">
                            <div class="shrink-0 w-10 h-10 rounded-full bg-gold/15 border border-gold flex items-center justify-center">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#F5A623" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-title text-text text-lg mb-1">Communauté</h3>
                                <p class="font-body text-muted text-sm">Des liens fraternels authentiques et durables</p>
                            </div>
                        </div>
                        <div class="flex gap-6 items-start">
                            <div class="shrink-0 w-10 h-10 rounded-full bg-gold/15 border border-gold flex items-center justify-center">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#F5A623" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-title text-text text-lg mb-1">Formation</h3>
                                <p class="font-body text-muted text-sm">Un parcours structuré vers la maturité spirituelle</p>
                            </div>
                        </div>
                        <div class="flex gap-6 items-start">
                            <div class="shrink-0 w-10 h-10 rounded-full bg-gold/15 border border-gold flex items-center justify-center">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#F5A623" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-title text-text text-lg mb-1">Mission</h3>
                                <p class="font-body text-muted text-sm">Formés pour servir au-delà des murs de l'église</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white/5 border border-gold/10 rounded-2xl p-8 hover:bg-gold/5 hover:border-gold/40 transition-all duration-300 reveal reveal-delay-1">
                        <div class="font-hero text-[56px] text-gold leading-none mb-2"><span class="counter" data-target="124">0</span>+</div>
                        <div class="font-body text-muted text-[13px] uppercase tracking-wider">Membres actifs</div>
                    </div>
                    <div class="bg-white/5 border border-gold/10 rounded-2xl p-8 hover:bg-gold/5 hover:border-gold/40 transition-all duration-300 reveal reveal-delay-2">
                        <div class="font-hero text-[56px] text-gold leading-none mb-2"><span class="counter" data-target="5">0</span></div>
                        <div class="font-body text-muted text-[13px] uppercase tracking-wider">Niveaux de progression</div>
                    </div>
                    <div class="bg-white/5 border border-gold/10 rounded-2xl p-8 hover:bg-gold/5 hover:border-gold/40 transition-all duration-300 reveal reveal-delay-3">
                        <div class="font-hero text-[56px] text-gold leading-none mb-2"><span class="counter" data-target="3">0</span> ans</div>
                        <div class="font-body text-muted text-[13px] uppercase tracking-wider">D'existence</div>
                    </div>
                    <div class="bg-white/5 border border-gold/10 rounded-2xl p-8 hover:bg-gold/5 hover:border-gold/40 transition-all duration-300 reveal reveal-delay-1">
                        <div class="font-hero text-[56px] text-gold leading-none mb-2"><span class="counter" data-target="100">0</span>%</div>
                        <div class="font-body text-muted text-[13px] uppercase tracking-wider">Numérique</div>
                    </div>
                </div>
            </div>
        </section>

        <section id="parcours" class="py-24 relative overflow-hidden" style="background-color: #0D1F3C;">
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: repeating-linear-gradient(45deg, #FFF 0, #FFF 1px, transparent 1px, transparent 20px);"></div>

            <div class="max-w-7xl mx-auto px-6 relative z-10 text-center mb-16 reveal">
                <span class="font-mono text-gold text-sm tracking-[3px] uppercase block mb-4">Votre Parcours</span>
                <h2 class="font-title text-white text-4xl md:text-[42px] mb-4">De Nouveau Membre à Missionnaire</h2>
                <p class="font-body text-white/70 text-lg">Un chemin de croissance pensé pour chaque disciple</p>
            </div>

            <div class="max-w-[1400px] mx-auto px-6 relative reveal reveal-delay-1">
                <div class="flex flex-col lg:flex-row items-center justify-center gap-6 lg:gap-4 xl:gap-8 perspective-1000">

                    <div class="tilt-card relative z-10 w-[260px] lg:w-[180px] xl:w-[200px] h-[220px] bg-bg dark:bg-black/60 border border-gold/20 rounded-[20px] shadow-[0_8px_32px_rgba(0,0,0,0.4)] flex flex-col items-center justify-center p-6 cursor-pointer overflow-hidden group">
                        <span class="absolute right-2 -bottom-4 font-hero text-[100px] text-gold/10 leading-none group-hover:text-gold/20 transition-colors">1</span>
                        <div class="tilt-card-inner flex flex-col items-center text-center">
                            <svg class="w-12 h-12 mb-4 text-text group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                            <h3 class="font-title text-text text-[15px] mb-2">Nouveau Membre</h3>
                            <p class="font-body text-muted text-[11px] leading-tight">Accueil et intégration dans la famille.</p>
                        </div>
                    </div>

                    <div class="hidden lg:block w-8 xl:w-16 h-1 bg-gradient-to-r from-gold/50 to-gold/20 rounded-full z-10 mx-2"></div>

                    <div class="tilt-card relative z-10 w-[260px] lg:w-[180px] xl:w-[200px] h-[220px] bg-bg dark:bg-black/60 border border-gold/20 rounded-[20px] shadow-[0_8px_32px_rgba(0,0,0,0.4)] flex flex-col items-center justify-center p-6 cursor-pointer overflow-hidden group">
                        <span class="absolute right-2 -bottom-4 font-hero text-[100px] text-gold/10 leading-none group-hover:text-gold/20 transition-colors">2</span>
                        <div class="tilt-card-inner flex flex-col items-center text-center">
                            <svg class="w-12 h-12 mb-4 text-text group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                            <h3 class="font-title text-text text-[15px] mb-2">Star</h3>
                            <p class="font-body text-muted text-[11px] leading-tight">Fondements validés, membre actif.</p>
                        </div>
                    </div>

                    <div class="hidden lg:block w-8 xl:w-16 h-1 bg-gradient-to-r from-gold/50 to-gold/20 rounded-full z-10 mx-2"></div>

                    <div class="tilt-card relative z-10 w-[260px] lg:w-[180px] xl:w-[200px] h-[220px] bg-bg dark:bg-black/60 border border-gold/20 rounded-[20px] shadow-[0_8px_32px_rgba(0,0,0,0.4)] flex flex-col items-center justify-center p-6 cursor-pointer overflow-hidden group">
                        <span class="absolute right-2 -bottom-4 font-hero text-[100px] text-gold/10 leading-none group-hover:text-gold/20 transition-colors">3</span>
                        <div class="tilt-card-inner flex flex-col items-center text-center">
                            <svg class="w-12 h-12 mb-4 text-text group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.2-1.1.6l-1.5 3 7.1 4.5-3.2 3.2-3.8-1.1c-.4-.1-.8.1-1 .5l-1 2 4.4 2.2 2.2 4.4 2-1c.4-.2.6-.6.5-1l-1.1-3.8 3.2-3.2 4.5 7.1 3-1.5c.4-.2.7-.6.6-1.1z"></path></svg>
                            <h3 class="font-title text-text text-[15px] mb-2">Pilote</h3>
                            <p class="font-body text-muted text-[11px] leading-tight">Engagé dans le service de l'église.</p>
                        </div>
                    </div>

                    <div class="hidden lg:block w-8 xl:w-16 h-1 bg-gradient-to-r from-gold/50 to-gold/20 rounded-full z-10 mx-2"></div>

                    <div class="tilt-card relative z-10 w-[260px] lg:w-[180px] xl:w-[200px] h-[220px] bg-bg dark:bg-black/60 border border-gold/20 rounded-[20px] shadow-[0_8px_32px_rgba(0,0,0,0.4)] flex flex-col items-center justify-center p-6 cursor-pointer overflow-hidden group">
                        <span class="absolute right-2 -bottom-4 font-hero text-[100px] text-gold/10 leading-none group-hover:text-gold/20 transition-colors">4</span>
                        <div class="tilt-card-inner flex flex-col items-center text-center">
                            <svg class="w-12 h-12 mb-4 text-text group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 22h16"></path><path d="M4 2h16"></path><path d="M6 2v20"></path><path d="M10 2v20"></path><path d="M14 2v20"></path><path d="M18 2v20"></path></svg>
                            <h3 class="font-title text-text text-[15px] mb-2">Pilier</h3>
                            <p class="font-body text-muted text-[11px] leading-tight">Leader solide et modèle pour la communauté.</p>
                        </div>
                    </div>

                    <div class="hidden lg:block w-8 xl:w-16 h-1 bg-gradient-to-r from-gold/50 to-gold/20 rounded-full z-10 mx-2"></div>

                    <div class="tilt-card relative z-10 w-[260px] lg:w-[180px] xl:w-[200px] h-[220px] bg-bg dark:bg-black/60 border border-gold/20 rounded-[20px] shadow-[0_8px_32px_rgba(0,0,0,0.4)] flex flex-col items-center justify-center p-6 cursor-pointer overflow-hidden group">
                        <span class="absolute right-2 -bottom-4 font-hero text-[100px] text-gold/10 leading-none group-hover:text-gold/20 transition-colors">5</span>
                        <div class="tilt-card-inner flex flex-col items-center text-center">
                            <svg class="w-12 h-12 mb-4 text-text group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                            <h3 class="font-title text-text text-[15px] mb-2">Missionnaire</h3>
                            <p class="font-body text-muted text-[11px] leading-tight">Envoyé pour implanter ou diriger au-delà.</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section id="formations" class="py-24 bg-bg relative">
            <div class="max-w-4xl mx-auto px-6 text-center reveal">
                <div class="inline-block p-4 rounded-full bg-gold/10 mb-6 border border-gold/20">
                    <i class="fas fa-graduation-cap text-4xl text-gold"></i>
                </div>
                <h2 class="font-title text-text text-4xl md:text-[42px] mb-4">Votre Parcours de Formation</h2>
                <p class="font-body text-text/70 text-lg mb-10">Poursuivez votre croissance spirituelle. Nos modules sont spécialement conçus pour vous accompagner à chaque étape.</p>

                @if ($formations->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 text-left">
                    @foreach ($formations as $module)
                    <div class="bg-white/5 border border-gold/20 rounded-xl p-6 hover:bg-gold/5 hover:border-gold/40 transition-all duration-300 group">
                        <i class="fas {{ $module->icone ?? 'fa-book' }} text-gold text-2xl mb-3"></i>
                        <h3 class="font-title text-text text-lg mb-2 group-hover:text-gold transition-colors">{{ $module->titre }}</h3>
                        <span class="inline-block text-xs px-3 py-1 rounded-full bg-gold/10 text-gold font-mono">{{ $module->categorie }}</span>
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="bg-white/5 border border-gold/20 rounded-2xl p-8 md:p-12 shadow-[0_10px_40px_rgba(0,0,0,0.1)] relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-gold to-gold-light group-hover:scale-y-150 transition-transform"></div>
                    <h3 class="font-title text-text text-2xl mb-3">Prêt à continuer ?</h3>
                    <p class="font-body text-muted text-sm md:text-base mb-8 max-w-lg mx-auto">Reprenez là où vous vous êtes arrêté. Accédez à votre espace pour voir vos modules en cours et valider votre progression.</p>
                    <a href="{{ route('login') }}" class="inline-block px-8 py-4 bg-gold text-marine font-body rounded-full hover:scale-105 hover:shadow-[0_0_20px_rgba(245,166,35,0.4)] transition-all duration-300 font-bold">
                        Reprendre mes formations <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </section>

        <section id="evenements" class="py-24 bg-bg dark:bg-black">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16 reveal">
                    <h2 class="font-title text-text text-4xl md:text-[42px] mb-4">Ne manquez aucun rassemblement</h2>
                </div>

                <div class="flex flex-col gap-4 max-w-4xl mx-auto mb-12">
                    @forelse ($evenements as $event)
                    <div class="bg-surface dark:bg-white/5 border border-border dark:border-white/10 shadow-sm dark:shadow-none rounded-2xl p-6 flex flex-col md:flex-row items-start md:items-center gap-8 hover:bg-white dark:hover:bg-white/10 hover:shadow-md hover:border-gold/50 dark:hover:border-gold/50 transition-all duration-300 reveal reveal-delay-1 group cursor-pointer">
                        <div class="shrink-0 text-center md:border-r md:border-border dark:md:border-white/10 md:pr-8 min-w-[120px]">
                            <span class="block font-hero text-gold text-5xl leading-none mb-1">{{ $event->date_debut->format('d') }}</span>
                            <span class="block font-body text-text uppercase tracking-widest text-xs">{{ \Carbon\Carbon::parse($event->date_debut)->translatedFormat('F Y') }}</span>
                        </div>
                        <div class="flex-grow">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="bg-green/20 text-green font-mono text-[10px] uppercase tracking-wide px-2 py-1 rounded animate-pulse">À venir</span>
                                <span class="font-body text-muted text-sm"><i class="far fa-clock mr-1"></i> {{ $event->date_debut->format('H:i') }}</span>
                            </div>
                            <h3 class="font-title text-text text-2xl mb-1 group-hover:text-gold transition-colors"><i class="fas {{ $event->icone ?? 'fa-calendar' }} text-gold mr-2 text-xl"></i>{{ $event->titre }}</h3>
                            <p class="font-body text-muted text-sm"><i class="fas fa-map-marker-alt mr-1"></i> {{ $event->lieu }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12 text-muted font-body">
                        <i class="fas fa-calendar-times text-4xl mb-4 text-gold/50"></i>
                        <p>Aucun événement à venir pour le moment.</p>
                    </div>
                    @endforelse
                </div>

                <div class="text-center reveal">
                    <a href="{{ route('login') }}" class="inline-block px-8 py-3 text-gold font-body hover:text-text transition-colors">
                        Voir tous les événements →
                    </a>
                </div>
            </div>
        </section>

        <section class="py-24 bg-surface dark:bg-black/50 relative overflow-hidden">
            <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(#FFF 1px, transparent 1px); background-size: 50px 50px;"></div>

            <div class="max-w-4xl mx-auto px-6 relative z-10 text-center reveal">
                <h2 class="font-title text-text text-4xl mb-16">Témoignages</h2>

                <div class="relative min-h-[300px] flex items-center justify-center">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 font-hero text-[150px] text-gold opacity-[0.08] leading-none select-none">" "</div>

                    <div id="testimonial-container" class="relative w-full overflow-hidden">
                        <div id="testimonial-track" class="flex transition-transform duration-500 ease-in-out">
                            <div class="w-full shrink-0 flex flex-col items-center text-center px-4">
                                <p class="font-hero italic text-text text-2xl md:text-3xl leading-relaxed mb-8 max-w-3xl">
                                    "La plateforme EJP a complètement changé ma façon de suivre mon parcours. Je vois exactement où j'en suis et ce qu'il me reste à accomplir."
                                </p>
                                <div class="flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-full bg-gold/20 border border-gold flex items-center justify-center text-gold font-bold mb-3">MK</div>
                                    <h4 class="font-title text-text text-lg">Marie K.</h4>
                                    <span class="font-body text-muted text-xs uppercase tracking-widest mt-1"><i class="fas fa-star text-gold mr-1"></i> Star</span>
                                    <div class="text-gold text-xs mt-2">★★★★★</div>
                                </div>
                            </div>

                            <div class="w-full shrink-0 flex flex-col items-center text-center px-4">
                                <p class="font-hero italic text-text text-2xl md:text-3xl leading-relaxed mb-8 max-w-3xl">
                                    "Les formations vidéo sont claires et bien organisées. En quelques semaines, j'ai pu compléter le parcours initial et faire ma demande de progression."
                                </p>
                                <div class="flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-full bg-blue-500/20 border border-blue-500 flex items-center justify-center text-blue-400 font-bold mb-3">JA</div>
                                    <h4 class="font-title text-text text-lg">Jean A.</h4>
                                    <span class="font-body text-muted text-xs uppercase tracking-widest mt-1"><i class="fas fa-paper-plane text-blue-400 mr-1"></i> Pilote</span>
                                    <div class="text-gold text-xs mt-2">★★★★★</div>
                                </div>
                            </div>

                            <div class="w-full shrink-0 flex flex-col items-center text-center px-4">
                                <p class="font-hero italic text-text text-2xl md:text-3xl leading-relaxed mb-8 max-w-3xl">
                                    "Mon Chef peut suivre mon évolution en temps réel. C'est exactement ce dont notre église avait besoin pour grandir de façon organisée."
                                </p>
                                <div class="flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-full bg-green/20 border border-green flex items-center justify-center text-green font-bold mb-3">ES</div>
                                    <h4 class="font-title text-text text-lg">Esther S.</h4>
                                    <span class="font-body text-muted text-xs uppercase tracking-widest mt-1"><i class="fas fa-university text-green mr-1"></i> Pilier</span>
                                    <div class="text-gold text-xs mt-2">★★★★★</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="cta" class="py-32 relative overflow-hidden bg-bg dark:bg-black">
            <div class="absolute inset-0 bg-gradient-to-br from-marine to-gold/40 opacity-80"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gold/20 rounded-full blur-[100px]"></div>

            <div class="max-w-3xl mx-auto px-6 relative z-10 text-center reveal">
                <svg class="w-16 h-16 mx-auto text-white mb-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M12 2L2 12l10 10 10-10L12 2z"></path><path d="M12 22v-8"></path><path d="M12 10V2"></path><path d="M2 12h8"></path><path d="M14 12h8"></path></svg>

                <h2 class="font-hero text-white text-5xl md:text-[56px] leading-tight mb-6">Prêt à commencer votre parcours ?</h2>
                <p class="font-body text-white/80 text-lg mb-12 max-w-xl mx-auto">
                    Votre compte a déjà été créé par l'administration. Connectez-vous dès maintenant pour accéder à vos formations.
                </p>

                <a href="{{ route('login') }}" class="inline-block px-10 py-5 bg-white text-marine font-body font-bold rounded-full shadow-[0_0_50px_rgba(255,255,255,0.3)] hover:scale-105 hover:shadow-[0_0_60px_rgba(255,255,255,0.5)] transition-all duration-300 relative group">
                    Accéder à mon espace membre →
                    <div class="absolute inset-0 rounded-full border border-white animate-ping opacity-20 group-hover:opacity-40"></div>
                </a>
            </div>
        </section>

        <section id="suggestions" class="py-24 bg-surface dark:bg-black/50 border-t border-border relative">
            <div class="max-w-4xl mx-auto px-6 text-center reveal">
                <div class="inline-block p-4 rounded-full bg-gold/10 mb-6">
                    <i class="fas fa-lightbulb text-4xl text-gold"></i>
                </div>
                <h2 class="font-title text-text text-4xl mb-4">Boîte à Suggestions</h2>
                <p class="font-body text-text/70 text-lg mb-8 max-w-2xl mx-auto">Une idée pour améliorer la plateforme ou pour l'EJP ? Votre avis compte énormément pour nous. N'hésitez pas à nous en faire part (anonymement si vous le souhaitez).</p>
                <button onclick="openSuggestionModal()" class="inline-block px-8 py-3 bg-transparent border-2 border-gold text-gold font-body rounded-full hover:bg-gold hover:text-black transition-colors font-bold shadow-[0_0_15px_rgba(245,166,35,0.1)]">
                    Faire une suggestion <i class="fas fa-comment-dots ml-2"></i>
                </button>
            </div>
        </section>
    </main>

    <footer class="bg-[#060E1A] dark:bg-black border-t border-border pt-20 pb-10 border-t border-gold/20 relative z-10">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-12 mb-16">
            <div>
                <span class="font-hero text-gold text-3xl font-bold block mb-4">EJP</span>
                <p class="font-body text-white/80 text-sm italic mb-4 leading-relaxed">
                    « Vous, au contraire, vous êtes une race élue, un sacerdoce royal, une nation sainte, un peuple acquis, afin que vous annonciez les vertus de celui qui vous a appelés des ténèbres à son admirable lumière. »
                    <br><span class="text-gold font-bold not-italic mt-2 inline-block">— 1 Pierre 2:9</span>
                </p>
                <div class="flex gap-4 mb-6">
                    <a href="https://www.instagram.com/ejp__agoelogope/" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-gold hover:text-black hover:border-gold transition-all duration-300"><i class="fab fa-instagram text-lg"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-gold hover:text-black hover:border-gold transition-all duration-300"><i class="fab fa-whatsapp text-lg"></i></a>
                    <a href="https://www.facebook.com/mjiiccagoelogope/" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-gold hover:text-black hover:border-gold transition-all duration-300"><i class="fab fa-facebook-f text-lg"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-gold hover:text-black hover:border-gold transition-all duration-300"><i class="fab fa-tiktok text-lg"></i></a>
                </div>
                <p class="font-body text-muted text-sm">© {{ date('Y') }} Église des Jeunes Prodiges.<br>Tous droits réservés.</p>
            </div>

            <div>
                <h4 class="font-mono text-gold text-xs uppercase tracking-[3px] mb-6">Navigation</h4>
                <ul class="space-y-3 font-body text-muted text-sm">
                    <li><a href="#hero" class="hover:text-gold transition-colors">Accueil</a></li>
                    <li><a href="#about" class="hover:text-gold transition-colors">À propos</a></li>
                    <li><a href="#parcours" class="hover:text-gold transition-colors">Le Parcours</a></li>
                    <li><a href="#formations" class="hover:text-gold transition-colors">Formations</a></li>
                    <li><a href="#evenements" class="hover:text-gold transition-colors">Événements</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-mono text-gold text-xs uppercase tracking-[3px] mb-6">Espace Membres</h4>
                <p class="font-body text-muted text-sm mb-4">Accédez directement à votre espace personnel.</p>
                <a href="{{ route('login') }}" class="inline-block px-6 py-2 border border-gold/50 text-gold hover:bg-gold/10 transition-colors rounded text-sm mb-4">Se connecter</a>
                <p class="font-body text-muted text-xs opacity-70">Vous n'avez pas de compte ou vous rencontrez un problème ? Contactez-nous au : <br><a href="tel:+2250000000000" class="text-gold font-bold hover:underline mt-1 inline-block text-sm">+228 XX XX XX XX</a></p>
            </div>
        </div>

        <div class="text-center font-body text-muted/30 text-[10px] uppercase tracking-widest pb-6">
            Made by EJP · Version 1.0 · {{ date('Y') }}
        </div>
    </footer>

    <button onclick="toggleTheme()" class="fixed bottom-8 right-8 z-50 w-14 h-14 rounded-full bg-primary text-primary-text shadow-2xl flex items-center justify-center text-xl hover:scale-110 transition-transform border border-border">
        <i class="fas fa-moon dark:hidden"></i>
        <i class="fas fa-sun hidden dark:block"></i>
    </button>

    <div id="suggestion-modal" class="fixed inset-0 z-[100] hidden items-center justify-center">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeSuggestionModal()"></div>
        <div class="relative w-full max-w-lg bg-bg rounded-2xl shadow-[0_20px_60px_rgba(0,0,0,0.4)] p-8 m-4 transform scale-95 opacity-0 transition-all duration-300 border border-border" id="suggestion-modal-content">
            <button onclick="closeSuggestionModal()" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-surface text-muted hover:text-text hover:bg-white/10 transition-colors"><i class="fas fa-times"></i></button>
            <h3 class="font-title text-text text-2xl mb-2"><i class="fas fa-lightbulb text-gold mr-2"></i>Votre Suggestion</h3>
            <p class="font-body text-muted text-sm mb-6">Partagez vos idées pour l'église ou la plateforme.</p>

            <form method="POST" action="{{ route('suggestions.store') }}" class="space-y-5 font-body text-left">
                @csrf
                <div>
                    <label class="block text-sm text-text font-bold mb-1">Catégorie *</label>
                    <select name="categorie" required class="w-full bg-surface border border-border rounded-lg px-4 py-2.5 text-text focus:outline-none focus:border-gold transition-colors appearance-none">
                        <option value="" disabled selected>Choisissez une catégorie...</option>
                        <option value="ejp">Pour l'EJP (Église)</option>
                        <option value="plateforme">Pour la plateforme Web</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-text font-bold mb-1">Nom (Optionnel)</label>
                    <input type="text" name="nom" class="w-full bg-surface border border-border rounded-lg px-4 py-2.5 text-text focus:outline-none focus:border-gold transition-colors" placeholder="Anonyme">
                </div>
                <div>
                    <label class="block text-sm text-text font-bold mb-1">Votre idée / suggestion *</label>
                    <textarea name="message" required rows="4" class="w-full bg-surface border border-border rounded-lg px-4 py-2.5 text-text focus:outline-none focus:border-gold transition-colors" placeholder="Décrivez votre idée ici..."></textarea>
                </div>
                <button type="submit" class="w-full py-3.5 bg-gold text-marine rounded-lg font-bold hover:bg-gold-light transition-colors flex justify-center items-center gap-2 mt-2 shadow-lg hover:scale-[1.02] transform">
                    Envoyer ma suggestion <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            setTimeout(() => {
                const loader = document.getElementById('loading-screen');
                if (loader) {
                    loader.style.opacity = '0';
                    setTimeout(() => loader.style.display = 'none', 500);
                }
            }, 800);
        });

        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 80) navbar.classList.add('scrolled');
            else navbar.classList.remove('scrolled');
        });

        const burgerBtn = document.getElementById('burger-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileLinks = document.querySelectorAll('.mobile-link');
        let menuOpen = false;

        burgerBtn.addEventListener('click', () => {
            menuOpen = !menuOpen;
            burgerBtn.classList.toggle('menu-open');
            mobileMenu.style.transform = menuOpen ? 'translateY(0)' : 'translateY(-100%)';
        });

        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                menuOpen = false;
                burgerBtn.classList.remove('menu-open');
                mobileMenu.style.transform = 'translateY(-100%)';
            });
        });

        const slides = document.querySelectorAll('.hero-slide');
        const indicators = document.querySelectorAll('.slide-indicator');
        const subtitle = document.getElementById('hero-subtitle');
        let subtitles = [
            "Une communauté soudée par la foi",
            "Grandissez dans votre parcours spirituel",
            "La jeunesse au service de Dieu",
            "Formez-vous. Progressez. Servez.",
            "Impactez votre génération et le monde"
        ];
        let currentSlide = 0;
        let slideInterval;

        function goToSlide(index) {
            slides[currentSlide].classList.remove('active');
            indicators[currentSlide].classList.remove('active');
            currentSlide = index;
            slides[currentSlide].classList.add('active');
            indicators[currentSlide].classList.add('active');
            subtitle.style.opacity = 0;
            setTimeout(() => {
                subtitle.innerText = subtitles[currentSlide];
                subtitle.style.opacity = 1;
            }, 300);
        }

        function startCarousel() {
            slideInterval = setInterval(() => {
                goToSlide((currentSlide + 1) % slides.length);
            }, 5000);
        }
        startCarousel();

        setTimeout(() => {
            const title1 = document.getElementById('hero-title-1');
            const title2 = document.getElementById('hero-title-2');

            const animateText = (el) => {
                const words = el.innerText.split(' ');
                el.innerHTML = '';
                words.forEach((word, i) => {
                    const span = document.createElement('span');
                    span.innerHTML = word + '&nbsp;';
                    span.className = 'word';
                    span.style.animationDelay = `${i * 80 + 500}ms`;
                    el.appendChild(span);
                    setTimeout(() => span.classList.add('animate'), 50);
                });
            };

            animateText(title1);
            setTimeout(() => animateText(title2), 400);
        }, 1000);

        const revealElements = document.querySelectorAll('.reveal');
        const counters = document.querySelectorAll('.counter');
        let countersStarted = false;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    if (entry.target.querySelector('.counter') && !countersStarted) {
                        countersStarted = true;
                        counters.forEach(counter => {
                            const target = +counter.getAttribute('data-target');
                            const duration = 2000;
                            const step = target / (duration / 16);
                            let current = 0;
                            const updateCounter = () => {
                                current += step;
                                if (current < target) {
                                    counter.innerText = Math.ceil(current);
                                    requestAnimationFrame(updateCounter);
                                } else {
                                    counter.innerText = target;
                                }
                            };
                            updateCounter();
                        });
                    }
                }
            });
        }, { threshold: 0.15 });

        revealElements.forEach(el => observer.observe(el));

        const tiltCards = document.querySelectorAll('.tilt-card');
        tiltCards.forEach(card => {
            card.addEventListener('mousemove', e => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = ((y - centerY) / centerY) * -8;
                const rotateY = ((x - centerX) / centerX) * 8;
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg)`;
            });
        });

        const track = document.getElementById('testimonial-track');
        if (track) {
            let tIndex = 0;
            const tCount = 3;
            setInterval(() => {
                tIndex = (tIndex + 1) % tCount;
                track.style.transform = `translateX(-${tIndex * 100}%)`;
            }, 4000);
        }

        function openSuggestionModal() {
            const modal = document.getElementById('suggestion-modal');
            const content = document.getElementById('suggestion-modal-content');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
        function closeSuggestionModal() {
            const modal = document.getElementById('suggestion-modal');
            const content = document.getElementById('suggestion-modal-content');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }
    </script>

</body>
</html>