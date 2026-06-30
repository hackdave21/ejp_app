@extends('layouts.chef')

@section('title', 'Ma Famille | EJP Chef')

@section('greeting')
    <i class="fas fa-users text-accent"></i> Ma Famille (Disciples)
@endsection

@section('content')
    <style>
        .badge-star  { background-color: rgba(245,166,35,0.12); color: #F5A623; border: 1px solid rgba(245,166,35,0.3); }
        .badge-pilot { background-color: rgba(59,130,246,0.12); color: #3B82F6; border: 1px solid rgba(59,130,246,0.3); }
        .badge-new   { background-color: rgba(156,163,175,0.12); color: #9CA3AF; border: 1px solid rgba(156,163,175,0.25); }
    </style>

    <div class="flex items-center justify-between mb-6">
        <p class="text-xs text-muted">Gérez le suivi pastoral de vos {{ count($membres) }} membres.</p>
        <div class="relative">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-muted text-sm"></i>
            <input type="text" placeholder="Rechercher un disciple..." class="bg-bg border border-border rounded-xl py-2.5 px-10 text-sm focus:ring-1 focus:ring-chef outline-none w-64">
        </div>
    </div>

    <div class="bg-surface rounded-3xl border border-border shadow-sm overflow-hidden animate-fade-in-up">
        <table class="w-full text-left text-sm">
            <thead class="bg-surface/50">
                <tr class="text-muted text-[10px] font-bold uppercase tracking-widest">
                    <th class="py-4 px-8">Disciple</th>
                    <th class="py-4 px-8">Niveau Actuel</th>
                    <th class="py-4 px-8">Formations</th>
                    <th class="py-4 px-8">Assiduité (Mois)</th>
                    <th class="py-4 px-8 text-right">Suivi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border/50">
                @foreach ($membres as $membre)
                @php
                    $initials = strtoupper(substr($membre->prenom, 0, 1)) . strtoupper(substr($membre->nom, 0, 1));
                    $badgeClass = match($membre->statut) {
                        'star' => 'badge-star',
                        'pilote' => 'badge-pilot',
                        'pilier' => 'badge-pilot',
                        default => 'badge-new',
                    };
                    $icon = match($membre->statut) {
                        'star' => 'fa-star',
                        'pilote' => 'fa-plane',
                        'pilier' => 'fa-university',
                        'missionnaire' => 'fa-globe',
                        default => 'fa-seedling',
                    };
                    $total = $membre->formationsSuivi->count();
                    $completed = $membre->formationsSuivi->where('vu', true)->count();
                    $pct = $total > 0 ? round(($completed / $total) * 100) : 0;
                @endphp
                <tr class="hover:bg-surface/50 transition-colors group cursor-pointer" onclick="openMemberDrawer('{{ $membre->full_name }}')">
                    <td class="py-4 px-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-accent text-white flex items-center justify-center font-bold text-xs shadow-sm">{{ $initials }}</div>
                            <div>
                                <p class="font-bold text-primary">{{ $membre->full_name }}</p>
                                <p class="text-xs text-muted">{{ $membre->telephone }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-8">
                        <span class="{{ $badgeClass }} px-3 py-1 text-xs font-bold rounded-full inline-flex items-center gap-1">
                            <i class="fas {{ $icon }}"></i> {{ ucfirst(str_replace('_', ' ', $membre->statut)) }}
                        </span>
                    </td>
                    <td class="py-4 px-8">
                        <div class="flex items-center gap-3">
                            <div class="flex-1 h-2 bg-surface rounded-full overflow-hidden w-24">
                                <div class="h-full {{ $pct >= 100 ? 'bg-success' : ($pct >= 50 ? 'bg-accent' : 'bg-danger') }}" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="text-xs font-bold text-muted">{{ $pct }}%</span>
                        </div>
                    </td>
                    <td class="py-4 px-8">
                        <div class="flex gap-1 text-[10px]">
                            @for ($i = 0; $i < 4; $i++)
                                <span class="w-2 h-6 bg-surface rounded-sm opacity-100"></span>
                            @endfor
                        </div>
                    </td>
                    <td class="py-4 px-8 text-right">
                        <button class="w-8 h-8 rounded-lg bg-surface text-muted group-hover:bg-chef group-hover:text-white transition-all">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="member-drawer" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-dark/60 backdrop-blur-sm" onclick="document.getElementById('member-drawer').classList.add('hidden')"></div>
        <div class="absolute right-0 top-0 bottom-0 w-full max-w-xl bg-surface shadow-2xl animate-fade-in-up flex flex-col">
            <header class="p-8 bg-surface border-b border-border flex items-start justify-between shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gray-200 text-muted flex items-center justify-center font-bold text-xl shadow-sm" id="drawer-avatar">--</div>
                    <div>
                        <h3 class="text-xl font-bold text-primary font-serif" id="drawer-name">Membre</h3>
                        <p class="text-xs text-muted mt-1">Membre de la famille</p>
                    </div>
                </div>
                <button onclick="document.getElementById('member-drawer').classList.add('hidden')" class="w-8 h-8 rounded-full hover:bg-gray-200 text-muted transition-colors flex items-center justify-center"><i class="fas fa-times"></i></button>
            </header>
            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar space-y-8">
                <div class="flex gap-2">
                    <a href="tel:#" class="flex-1 py-3 bg-bg text-primary font-bold text-xs rounded-xl hover:bg-primary hover:text-primary-text transition-all flex items-center justify-center gap-2"><i class="fas fa-phone"></i> Appeler</a>
                    <a href="mailto:#" class="flex-1 py-3 bg-bg text-primary font-bold text-xs rounded-xl hover:bg-primary hover:text-primary-text transition-all flex items-center justify-center gap-2"><i class="fas fa-envelope"></i> E-mail</a>
                    <a href="#" class="flex-1 py-3 bg-green-50 text-success font-bold text-xs rounded-xl hover:bg-success hover:text-white transition-all flex items-center justify-center gap-2"><i class="fab fa-whatsapp text-lg"></i> WhatsApp</a>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold text-muted uppercase tracking-widest mb-4 border-b border-border pb-2">Progression Globale</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 border border-border rounded-2xl">
                            <p class="text-xs text-muted mb-1">Formations</p>
                            <p class="text-xl font-bold text-primary">—</p>
                        </div>
                        <div class="p-4 border border-border rounded-2xl">
                            <p class="text-xs text-muted mb-1">Assiduité (30j)</p>
                            <p class="text-xl font-bold text-primary">—</p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-4 border-b border-border pb-2">
                        <h4 class="text-[10px] font-bold text-muted uppercase tracking-widest">Notes de Suivi Pastoral</h4>
                        <button class="text-xs text-chef font-bold hover:underline"><i class="fas fa-plus"></i> Ajouter</button>
                    </div>
                    <div class="bg-bg p-4 rounded-2xl">
                        <textarea class="w-full bg-transparent border-none text-sm text-primary resize-none outline-none placeholder-gray-400" placeholder="Rédigez une note de suivi..." rows="3"></textarea>
                        <div class="flex justify-end mt-2">
                            <button class="px-4 py-1.5 bg-chef text-white font-bold text-xs rounded-lg shadow-sm">Enregistrer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openMemberDrawer(name) {
        document.getElementById('drawer-name').innerText = name;
        const initials = name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
        document.getElementById('drawer-avatar').innerText = initials;
        document.getElementById('member-drawer').classList.remove('hidden');
    }
</script>
@endpush
