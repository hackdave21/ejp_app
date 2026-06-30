@extends('layouts.frontend')
@section('title', 'Mes Formations')
@section('page_title', 'Mes Formations')

@section('content')
<div class="bg-surface rounded-2xl p-6 shadow-sm border border-border mb-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex-1">
            <div class="flex justify-between items-end mb-2">
                <span class="text-sm font-bold text-primary">{{ $suivis->filter()->count() }} sur {{ $modules->count() }} formations complétées</span>
                <span class="text-sm font-bold text-accent">{{ $progress }}%</span>
            </div>
            <div class="w-full h-4 bg-surface rounded-full overflow-hidden">
                <div class="h-full bg-accent rounded-full transition-all duration-1000 ease-out" style="width: {{ $progress }}%"></div>
            </div>
        </div>
        <div class="flex gap-3">
            <span class="px-3 py-1 bg-success/10 text-success text-[10px] font-bold rounded-full border border-success/20 uppercase tracking-wider">Obligatoires <i class="fas fa-check-circle text-success"></i></span>
            <span class="px-3 py-1 bg-primary/5 text-primary/60 text-[10px] font-bold rounded-full border border-primary/10 uppercase tracking-wider">Optionnelles <i class="fas fa-book text-accent"></i></span>
        </div>
    </div>
</div>

<div class="flex flex-wrap gap-2 mb-8">
    <button class="filter-pill active px-4 py-2 rounded-full border border-border text-sm font-medium transition-all hover:border-primary" data-filter="all">Tous</button>
    <button class="filter-pill px-4 py-2 rounded-full border border-border text-sm font-medium transition-all hover:border-primary" data-filter="obligatoire">Obligatoires</button>
    <button class="filter-pill px-4 py-2 rounded-full border border-border text-sm font-medium transition-all hover:border-primary" data-filter="optionnel">Optionnels</button>
    <button class="filter-pill px-4 py-2 rounded-full border border-border text-sm font-medium transition-all hover:border-primary" data-filter="complete">Complétés</button>
    <button class="filter-pill px-4 py-2 rounded-full border border-border text-sm font-medium transition-all hover:border-primary" data-filter="locked">Non commencés</button>
</div>

<div class="space-y-4">
    @foreach ($modules as $index => $module)
    @php
        $isVu = $suivis[$module->id] ?? false;
    @endphp
    <div class="accordion-item bg-surface rounded-2xl shadow-sm border border-border overflow-hidden {{ $loop->first ? 'open' : '' }}">
        <button class="accordion-trigger w-full px-6 py-5 flex items-center justify-between bg-surface/50 hover:bg-surface transition-colors">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary text-accent rounded-xl flex items-center justify-center shadow-inner">
                    <i class="fas {{ $module->icone ?? 'fa-cross' }} text-xl"></i>
                </div>
                <div class="text-left">
                    <h3 class="font-bold text-primary">MODULE {{ $loop->iteration }} — {{ $module->titre }}</h3>
                    <span class="px-2 py-0.5 {{ ($module->categorie ?? 'obligatoire') === 'obligatoire' ? 'bg-danger/10 text-danger' : 'bg-primary/5 text-primary/60' }} text-[10px] font-bold rounded uppercase">{{ $module->categorie ?? 'Obligatoire' }}</span>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-xs font-bold {{ $isVu ? 'text-success' : 'text-muted' }} hidden sm:block">{{ $isVu ? 'Complété' : '0 Vidéos' }}</span>
                <i class="fas fa-chevron-down text-muted transition-transform chevron"></i>
            </div>
        </button>
        <div class="accordion-content">
            <div class="p-6">
                @if ($module->description)
                <div class="mb-6">
                    <p class="text-muted text-sm">{{ $module->description }}</p>
                </div>
                @endif
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @if ($module->video_url)
                    <div class="group bg-surface rounded-xl border border-border p-4 transition-all hover:-translate-y-1 hover:shadow-md">
                        <div class="relative aspect-video bg-black rounded-lg mb-4 overflow-hidden">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-play text-white/50 text-4xl group-hover:scale-110 transition-transform"></i>
                            </div>
                            @if ($isVu)
                            <div class="absolute top-3 right-3 px-2 py-1 bg-success text-white text-[10px] font-bold rounded flex items-center gap-1">
                                <i class="fas fa-check"></i> VU
                            </div>
                            @endif
                        </div>
                        <h4 class="font-bold text-primary mb-1">{{ $module->titre }}</h4>
                        <p class="text-xs text-muted mb-4">Durée: {{ $module->duree ?? 'N/A' }}</p>
                        <a href="{{ route('membre.formations.show', $module) }}" class="open-modal w-full py-2 bg-surface border border-border text-primary text-sm font-bold rounded-lg hover:bg-surface transition-colors block text-center">
                            @if($isVu) Revoir @else Regarder @endif
                        </a>
                    </div>
                    @else
                    <div class="text-center text-muted italic py-8 col-span-full">
                        <i class="fas fa-info-circle mb-2 block text-2xl"></i>
                        Aucune vidéo disponible pour ce module.
                    </div>
                    @endif
                </div>
                @if (!$isVu)
                <div class="mt-6">
                    <form method="POST" action="{{ route('membre.formations.markSeen', $module) }}">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-accent text-primary font-bold rounded-xl hover:bg-accent/90 transition-all shadow-sm shadow-accent/20">
                            <i class="fas fa-check mr-2"></i> Marquer comme terminé
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@push('scripts')
<script>
    const accordionItems = document.querySelectorAll('.accordion-item');
    accordionItems.forEach(item => {
        const trigger = item.querySelector('.accordion-trigger');
        trigger.addEventListener('click', () => {
            const isOpen = item.classList.contains('open');
            if (!isOpen) item.classList.add('open');
            else item.classList.remove('open');
        });
    });

    const filterPills = document.querySelectorAll('.filter-pill');
    filterPills.forEach(pill => {
        pill.addEventListener('click', () => {
            filterPills.forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
        });
    });
</script>
@endpush
