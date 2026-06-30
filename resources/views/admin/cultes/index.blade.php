@extends('layouts.admin')

@section('title', 'Cultes')
@section('page_title', '<i class="fas fa-church text-accent"></i> Gestion des Cultes')

@section('content')
<div class="flex justify-end">
    <button id="add-culte-btn" class="bg-accent text-primary font-bold px-6 py-2.5 rounded-xl hover:scale-105 transition-transform flex items-center gap-2">
        <i class="fas fa-plus"></i> Programmer un culte
    </button>
</div>

@if (session('success'))
    <div class="p-4 bg-success/10 text-success rounded-2xl border border-success/20 text-sm font-bold">{{ session('success') }}</div>
@endif

@php
    $totalPresences = $cultes->sum(fn($c) => $c->total);
    $countPresences = $cultes->filter(fn($c) => $c->total > 0)->count();
    $moyennePresence = $countPresences > 0 ? round($totalPresences / $countPresences) : 0;
    $cultesCeMois = $cultes->filter(fn($c) => $c->date->isCurrentMonth())->count();
    $nextCulte = $cultes->filter(fn($c) => $c->date->isFuture())->sortBy('date')->first();
    $isToday = $nextCulte && $nextCulte->date->isToday();
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-surface p-6 rounded-3xl border border-border shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-50 text-primary rounded-2xl flex items-center justify-center text-xl">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <p class="text-xs text-muted font-bold uppercase">Présence Moyenne</p>
            <p class="text-2xl font-bold text-primary">{{ $moyennePresence }}</p>
        </div>
    </div>
    <div class="bg-surface p-6 rounded-3xl border border-border shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-green-50 text-success rounded-2xl flex items-center justify-center text-xl">
            <i class="fas fa-chart-line"></i>
        </div>
        <div>
            <p class="text-xs text-muted font-bold uppercase">Taux de Croissance</p>
            <p class="text-2xl font-bold text-primary">{{ $cultes->count() > 0 ? '+' . round($cultes->avg(fn($c) => $c->total > 0 ? 1 : 0) * 100) . '%' : '—' }}</p>
        </div>
    </div>
    <div class="bg-surface p-6 rounded-3xl border border-border shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-amber-50 text-accent rounded-2xl flex items-center justify-center text-xl">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div>
            <p class="text-xs text-muted font-bold uppercase">Cultes ce mois</p>
            <p class="text-2xl font-bold text-primary">{{ $cultesCeMois }}</p>
        </div>
    </div>
</div>

@if ($nextCulte)
<section class="bg-primary rounded-3xl p-8 text-primary-text relative overflow-hidden shadow-xl animate-fade-in-up">
    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
        <div>
            <div class="flex items-center gap-3 mb-4">
                @if ($isToday)
                <span class="bg-danger text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest animate-pulse-red">EN DIRECT</span>
                @endif
                <span class="text-white/60 text-sm">{{ $nextCulte->type }} • {{ $nextCulte->date->translatedFormat('j F Y') }}</span>
            </div>
            <h3 class="text-3xl font-serif font-bold mb-2">Thème : "{{ $nextCulte->theme }}"</h3>
            <p class="text-white/60 italic">Orateur : {{ $nextCulte->orateur }}</p>
        </div>
        <div class="flex gap-4">
            <button onclick="openPresenceModal({{ $nextCulte->id }}, {{ $nextCulte->hommes ?? 0 }}, {{ $nextCulte->femmes ?? 0 }}, {{ $nextCulte->enfants ?? 0 }})" class="px-6 py-3 bg-accent text-primary font-bold rounded-xl hover:bg-surface transition-colors">Point de Présence</button>
        </div>
    </div>
    <div class="absolute right-0 top-0 h-full w-1/4 bg-surface/5 skew-x-[-20deg] translate-x-1/2"></div>
</section>
@endif

<section>
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-primary font-serif">Historique des cultes</h3>
    </div>

    <div class="bg-surface rounded-3xl shadow-sm border border-border overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-surface border-b border-border">
                <tr class="text-muted text-[10px] font-bold uppercase tracking-widest">
                    <th class="py-4 px-8">Date & Type</th>
                    <th class="py-4 px-8">Thème</th>
                    <th class="py-4 px-8">Orateur</th>
                    <th class="py-4 px-8 text-center">Présence</th>
                    <th class="py-4 px-8 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($cultes as $culte)
                <tr class="hover:bg-surface transition-colors group">
                    <td class="py-6 px-8">
                        <p class="font-bold text-primary">{{ $culte->date->translatedFormat('j F Y') }}</p>
                        <p class="text-[10px] text-muted uppercase">{{ $culte->type }}</p>
                    </td>
                    <td class="py-6 px-8 font-serif text-primary">{{ $culte->theme }}</td>
                    <td class="py-6 px-8 text-muted">{{ $culte->orateur }}</td>
                    <td class="py-6 px-8 text-center">
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-primary rounded-lg font-bold text-xs">
                            <i class="fas fa-users text-[10px]"></i> {{ $culte->total }}
                        </div>
                    </td>
                    <td class="py-6 px-8 text-right">
                        <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="openPresenceModal({{ $culte->id }}, {{ $culte->hommes ?? 0 }}, {{ $culte->femmes ?? 0 }}, {{ $culte->enfants ?? 0 }})" class="w-8 h-8 rounded-lg bg-surface text-muted hover:bg-primary hover:text-primary-text transition-all"><i class="fas fa-chart-bar text-xs"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center text-muted">
                        <i class="fas fa-church text-3xl mb-2"></i>
                        <p class="font-bold">Aucun culte enregistré</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<div id="add-drawer" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-dark/60 backdrop-blur-sm" onclick="document.getElementById('add-drawer').classList.add('hidden')"></div>
    <div class="absolute right-0 top-0 bottom-0 w-full max-w-2xl bg-surface shadow-2xl animate-slide-in-right flex flex-col">
        <header class="p-10 border-b border-border flex items-center justify-between shrink-0">
            <h3 class="text-2xl font-bold text-primary font-serif">Programmer un Culte</h3>
            <button onclick="document.getElementById('add-drawer').classList.add('hidden')" class="w-10 h-10 rounded-full hover:bg-surface text-muted transition-colors"><i class="fas fa-times text-xl"></i></button>
        </header>

        <form method="POST" action="{{ route('admin.cultes.store') }}" class="flex-1 flex flex-col">
            @csrf
            <div class="flex-1 overflow-y-auto p-10 custom-scrollbar space-y-8">
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Thème</label>
                    <input type="text" name="theme" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-1 focus:ring-accent" placeholder="Ex: La Puissance du Saint Esprit">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Date</label>
                        <input type="date" name="date" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-1 focus:ring-accent">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Type</label>
                        <select name="type" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm text-muted outline-none">
                            <option value="Dimanche">Culte de Dimanche</option>
                            <option value="Semaine">Culte de Semaine</option>
                            <option value="Jeune">Culte de Jeûne</option>
                            <option value="Veillée">Veillée de Prière</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-muted uppercase tracking-widest">Orateur(trice)</label>
                    <input type="text" name="orateur" required class="w-full bg-bg border-none rounded-xl py-3 px-4 text-sm focus:ring-1 focus:ring-accent" placeholder="Nom du prédicateur">
                </div>
            </div>

            <footer class="p-10 border-t border-border shrink-0">
                <button type="submit" class="w-full py-4 bg-primary text-primary-text font-bold rounded-2xl shadow-lg shadow-primary/20">Enregistrer le culte</button>
            </footer>
        </form>
    </div>
</div>

<form id="presenceForm" method="POST" class="hidden">
    @csrf @method('PUT')
    <div id="presence-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="modal-overlay absolute inset-0" onclick="document.getElementById('presence-modal').classList.add('hidden')"></div>
        <div class="relative w-full max-w-md bg-surface rounded-3xl overflow-hidden shadow-2xl p-8">
            <h3 class="text-2xl font-bold text-primary font-serif mb-6">Point de Présence</h3>
            <div class="space-y-6">
                <div class="grid grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted uppercase">Hommes</label>
                        <input type="number" name="hommes" id="presenceHommes" required min="0" class="w-full bg-surface border-none rounded-xl py-3 px-4 text-center font-bold text-primary">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted uppercase">Femmes</label>
                        <input type="number" name="femmes" id="presenceFemmes" required min="0" class="w-full bg-surface border-none rounded-xl py-3 px-4 text-center font-bold text-primary">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted uppercase">Enfants</label>
                        <input type="number" name="enfants" id="presenceEnfants" required min="0" class="w-full bg-surface border-none rounded-xl py-3 px-4 text-center font-bold text-primary">
                    </div>
                </div>
                <div class="p-4 bg-primary rounded-2xl text-center">
                    <p class="text-xs text-white/60 font-bold uppercase mb-1">Total Général</p>
                    <p id="presenceTotal" class="text-3xl font-bold text-accent">0</p>
                </div>
                <button type="submit" class="w-full py-4 bg-primary text-primary-text font-bold rounded-2xl shadow-lg">Enregistrer les données</button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.getElementById('add-culte-btn').onclick = () => {
        document.getElementById('add-drawer').classList.remove('hidden');
    };

    function openPresenceModal(id, hommes, femmes, enfants) {
        document.getElementById('presenceHommes').value = hommes;
        document.getElementById('presenceFemmes').value = femmes;
        document.getElementById('presenceEnfants').value = enfants;
        updateTotal();
        document.getElementById('presenceForm').action = '/admin/cultes/' + id + '/presence';
        document.getElementById('presence-modal').classList.remove('hidden');
    }

    function updateTotal() {
        const h = parseInt(document.getElementById('presenceHommes').value) || 0;
        const f = parseInt(document.getElementById('presenceFemmes').value) || 0;
        const e = parseInt(document.getElementById('presenceEnfants').value) || 0;
        document.getElementById('presenceTotal').textContent = h + f + e;
    }

    document.getElementById('presenceHommes').addEventListener('input', updateTotal);
    document.getElementById('presenceFemmes').addEventListener('input', updateTotal);
    document.getElementById('presenceEnfants').addEventListener('input', updateTotal);

    document.querySelectorAll('#presence-modal .modal-overlay').forEach(el => {
        el.addEventListener('click', () => document.getElementById('presence-modal').classList.add('hidden'));
    });
</script>
@endpush
