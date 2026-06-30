@extends('layouts.frontend')
@section('title', 'Tableau de bord')
@section('page_title', 'Tableau de bord')

@section('content')
<section class="animate-slide-in-left">
    <div class="bg-white dark:bg-black border border-border dark:border-white/10 rounded-3xl p-8 text-text relative overflow-hidden shadow-sm dark:shadow-none">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-accent/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-60 h-60 bg-blue-400/5 rounded-full blur-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="text-center md:text-left">
                <h1 class="text-4xl font-serif mb-2 text-primary">Bonjour, {{ auth()->user()->prenom }} <i class="fas fa-hand-sparkles text-accent"></i></h1>
                <div class="flex items-center justify-center md:justify-start gap-3 mb-4">
                    <span class="inline-flex items-center gap-2 bg-accent/10 border border-accent/20 px-3 py-1 rounded-full text-accent text-sm font-bold">
                        <i class="fas fa-star animate-pulse-soft"></i>
                        Statut : {{ ucfirst(str_replace('_', ' ', auth()->user()->statut ?? 'Membre')) }}
                    </span>
                    <span class="text-muted text-sm">Membre depuis le {{ auth()->user()->created_at->format('d F Y') }}</span>
                </div>
                <p class="text-muted max-w-lg leading-relaxed">
                    Continuez votre excellent travail ! Vous êtes à quelques étapes seulement de devenir <strong class="text-primary font-bold">Pilote <i class="fas fa-plane text-accent ml-1"></i></strong>.
                </p>
            </div>
            <div class="hidden lg:block">
                <div class="relative w-32 h-32 flex items-center justify-center">
                    <div class="absolute inset-0 bg-accent/10 rounded-full animate-pulse-soft"></div>
                    <i class="fas fa-star text-6xl text-accent drop-shadow-[0_0_15px_rgba(245,166,35,0.6)]"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="grid grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-surface p-6 rounded-2xl shadow-sm border border-border card-hover animate-fade-in-up" style="animation-delay: 0.1s">
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 mb-4">
            <i class="fas fa-video text-xl"></i>
        </div>
        <p class="text-muted text-sm font-medium">Formations</p>
        <h3 class="text-2xl font-bold text-primary">{{ $stats['formations_vues'] }}/{{ $stats['formations_total'] }}</h3>
        <p class="text-[10px] text-success font-bold mt-1">{{ $stats['formations_total'] > 0 ? round($stats['formations_vues'] / $stats['formations_total'] * 100) : 0 }}% complété</p>
    </div>
    <div class="bg-surface p-6 rounded-2xl shadow-sm border border-border card-hover animate-fade-in-up" style="animation-delay: 0.2s">
        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600 mb-4">
            <i class="fas fa-check-circle text-xl"></i>
        </div>
        <p class="text-muted text-sm font-medium">Services</p>
        <h3 class="text-2xl font-bold text-primary">{{ $stats['evenements_venir'] }} sessions</h3>
        <p class="text-[10px] text-muted font-bold mt-1">Besoin : 12 pour Pilote</p>
    </div>
    <div class="bg-surface p-6 rounded-2xl shadow-sm border border-border card-hover animate-fade-in-up" style="animation-delay: 0.3s">
        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 mb-4">
            <i class="fas fa-calendar-check text-xl"></i>
        </div>
        <p class="text-muted text-sm font-medium">Ancienneté</p>
        <h3 class="text-2xl font-bold text-primary">{{ auth()->user()->created_at->diffInMonths() }} mois</h3>
        <p class="text-[10px] text-muted font-bold mt-1">EJP Template</p>
    </div>
    <div class="bg-surface p-6 rounded-2xl shadow-sm border border-border card-hover animate-fade-in-up" style="animation-delay: 0.4s">
        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600 mb-4">
            <i class="fas fa-trophy text-xl"></i>
        </div>
        <p class="text-muted text-sm font-medium">Niveau</p>
        <h3 class="text-2xl font-bold text-primary">{{ ucfirst(str_replace('_', ' ', auth()->user()->statut ?? 'Membre')) }}</h3>
        <p class="text-[10px] text-accent font-bold mt-1">Prochain : Pilote</p>
    </div>
</section>

<div class="grid lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-8">
        <div class="bg-surface p-8 rounded-3xl shadow-sm border border-border">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-bold text-primary">Ma progression dans les formations</h3>
                <a href="{{ route('membre.formations.index') }}" class="text-accent text-sm font-bold hover:underline">Voir tout →</a>
            </div>
            @php
                $dashboardProgress = $stats['formations_total'] > 0 ? round($stats['formations_vues'] / $stats['formations_total'] * 100) : 0;
            @endphp
            <div class="mb-10">
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-medium text-muted">Global</span>
                    <span class="text-sm font-bold text-primary">{{ $dashboardProgress }}%</span>
                </div>
                <div class="w-full h-3 bg-surface rounded-full overflow-hidden">
                    <div class="h-full bg-accent rounded-full transition-all duration-1000 ease-out" style="width: {{ $dashboardProgress }}%"></div>
                </div>
            </div>
            <div class="space-y-4">
                @foreach(\App\Models\FormationModule::where('statut', true)->orderBy('ordre')->take(3)->get() as $mod)
                @php $vu = \App\Models\FormationSuivi::where('user_id', auth()->id())->where('module_id', $mod->id)->value('vu'); @endphp
                <div class="flex items-center justify-between p-4 bg-surface rounded-xl border border-border hover:border-accent/30 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center @if($vu) bg-success/10 text-success @elseif($mod->statut) bg-amber-500/10 text-amber-500 @else bg-gray-200 text-muted @endif">
                            <i class="fas @if($vu) fa-check @elseif($mod->statut) fa-sync animate-spin @else fa-lock @endif"></i>
                        </div>
                        <div>
                            <p class="font-bold text-primary">{{ $mod->titre }}</p>
                            <p class="text-xs text-muted">Module {{ $mod->categorie ?? 'formation' }}</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold @if($vu) text-success @elseif($mod->statut) text-amber-500 @else text-muted @endif">
                        @if($vu) Complété @elseif($mod->statut) En cours @else Verrouillé @endif
                    </span>
                </div>
                @endforeach
            </div>
            <div class="mt-10">
                <button class="w-full bg-gray-200 text-muted font-bold py-4 rounded-2xl cursor-not-allowed group relative" disabled>
                    Faire une demande de progression vers Pilote
                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 bg-black text-white text-[10px] rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                        Complétez 100% des formations obligatoires et 12 sessions de service.
                    </div>
                </button>
            </div>
        </div>
    </div>

    <div class="space-y-8">
        <div class="bg-surface p-8 rounded-3xl shadow-sm border border-border h-full">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-bold text-primary">Notifications</h3>
                <a href="{{ route('membre.notifications.index') }}" class="text-primary/40 hover:text-primary"><i class="fas fa-ellipsis-h"></i></a>
            </div>
            <div class="space-y-6">
                @php $dashNotifs = \App\Models\Notification::where('user_id', auth()->id())->latest()->take(3)->get(); @endphp
                @forelse ($dashNotifs as $notif)
                <div class="flex gap-4">
                    <div class="w-10 h-10 shrink-0 rounded-full flex items-center justify-center
                        @if($notif->categorie === 'progression') bg-accent/20 text-accent
                        @elseif($notif->categorie === 'evenements') bg-green-100 text-green-600
                        @else bg-blue-100 text-blue-600 @endif">
                        <i class="fas @if($notif->categorie === 'progression') fa-star @elseif($notif->categorie === 'evenements') fa-calendar-alt @else fa-info-circle @endif"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-primary">{{ $notif->titre }}</p>
                        <p class="text-xs text-muted mt-1">{{ $notif->message }}</p>
                        <p class="text-[10px] text-muted mt-2">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-muted text-sm italic">Aucune notification récente.</p>
                @endforelse
            </div>
            @if ($dashNotifs->count() > 0)
            <a href="{{ route('membre.notifications.index') }}" class="block w-full mt-8 py-3 text-sm font-bold text-primary text-center hover:bg-surface rounded-xl border border-border transition-colors">
                Voir toutes les notifications
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
