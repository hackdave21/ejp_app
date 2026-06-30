@extends('layouts.frontend')
@section('title', $module->titre)
@section('page_title', 'Formation : ' . $module->titre)

@section('content')
<a href="{{ route('membre.formations.index') }}" class="inline-flex items-center gap-2 text-accent font-bold hover:underline mb-8">
    <i class="fas fa-arrow-left"></i> Retour aux formations
</a>

@if (session('success'))
<div class="mb-6 p-4 bg-success/10 text-success font-bold rounded-2xl border border-success/20 flex items-center gap-3">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div class="bg-surface rounded-3xl shadow-sm border border-border overflow-hidden">
    <div class="px-8 py-6 flex items-center justify-between bg-surface/50 border-b border-border">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-primary text-accent rounded-2xl flex items-center justify-center shadow-inner">
                <i class="fas {{ $module->icone ?? 'fa-cross' }} text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-primary font-serif">{{ $module->titre }}</h1>
                <span class="px-2 py-0.5 {{ ($module->categorie ?? 'obligatoire') === 'obligatoire' ? 'bg-danger/10 text-danger' : 'bg-primary/5 text-primary/60' }} text-[10px] font-bold rounded uppercase">{{ $module->categorie ?? 'Obligatoire' }}</span>
            </div>
        </div>
        <div>
            @if ($suivi->vu)
            <span class="px-4 py-2 bg-success/10 text-success text-sm font-bold rounded-xl flex items-center gap-2">
                <i class="fas fa-check-circle"></i> Terminé
            </span>
            @endif
        </div>
    </div>

    <div class="p-8">
        @if ($module->description)
        <p class="text-muted leading-relaxed mb-8 max-w-3xl">{{ $module->description }}</p>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if ($module->video_url)
            <div class="group bg-surface rounded-xl border border-border p-4 transition-all hover:-translate-y-1 hover:shadow-md {{ $suivi->vu ? 'border-2 border-success/30' : '' }}">
                <div class="relative aspect-video bg-black rounded-lg mb-4 overflow-hidden">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-play text-white/50 text-4xl group-hover:scale-110 transition-transform"></i>
                    </div>
                    @if ($suivi->vu)
                    <div class="absolute top-3 right-3 px-2 py-1 bg-success text-white text-[10px] font-bold rounded flex items-center gap-1">
                        <i class="fas fa-check"></i> VU
                    </div>
                    @else
                    <div class="absolute top-3 right-3 px-2 py-1 bg-accent text-white text-[10px] font-bold rounded flex items-center gap-1">
                        <i class="fas fa-sync fa-spin text-[8px]"></i> EN COURS
                    </div>
                    @endif
                </div>
                <h4 class="font-bold text-primary mb-1">{{ $module->titre }}</h4>
                <p class="text-xs text-muted mb-4">Durée: {{ $module->duree ?? 'N/A' }}</p>
                <div class="aspect-video mb-4">
                    <iframe src="{{ $module->video_url }}" class="w-full h-full rounded-lg" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
            @else
            <div class="col-span-full text-center text-muted italic py-12">
                <i class="fas fa-info-circle mb-2 block text-2xl"></i>
                Aucune vidéo disponible pour ce module.
            </div>
            @endif
        </div>

        <div class="mt-10 pt-6 border-t border-border">
            @if (!$suivi->vu)
            <form method="POST" action="{{ route('membre.formations.markSeen', $module) }}">
                @csrf
                <button type="submit" class="px-8 py-4 bg-accent text-primary font-bold rounded-2xl hover:bg-accent/90 transition-all shadow-lg shadow-accent/20 text-lg">
                    <i class="fas fa-check mr-2"></i> Terminer ce module
                </button>
            </form>
            @else
            <div class="flex items-center gap-4">
                <span class="px-6 py-3 bg-success/10 text-success font-bold rounded-2xl flex items-center gap-3">
                    <i class="fas fa-check-circle text-xl"></i> Module complété
                </span>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
