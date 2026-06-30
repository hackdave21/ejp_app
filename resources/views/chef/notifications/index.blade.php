@extends('layouts.chef')
@section('title', 'Notifications')
@section('page_title', 'Notifications')

@section('content')
@if (session('success'))
    <div class="mb-6 p-4 bg-success/10 border border-success/30 text-success rounded-xl flex items-center gap-3 text-sm">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="bg-surface border border-border rounded-3xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-muted/5 border-b border-border">
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-muted uppercase tracking-widest">Titre</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-muted uppercase tracking-widest">Message</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-muted uppercase tracking-widest">Catégorie</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-muted uppercase tracking-widest">Statut</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-muted uppercase tracking-widest">Reçue le</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($notifications as $notif)
                <tr class="hover:bg-muted/5 transition-colors {{ !$notif->lue ? 'bg-accent/5' : '' }}">
                    <td class="px-6 py-4">
                        <span class="font-bold text-sm text-primary {{ !$notif->lue ? 'text-accent' : '' }}">{{ $notif->titre }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-muted max-w-xs truncate">{{ $notif->message }}</td>
                    <td class="px-6 py-4">
                        @php
                            $catColors = ['info' => 'bg-blue-100 text-blue-600', 'evenements' => 'bg-purple-100 text-purple-600', 'formations' => 'bg-green-100 text-green-600', 'demandes' => 'bg-amber-100 text-amber-600'];
                        @endphp
                        <span class="px-3 py-1 text-[10px] font-bold uppercase {{ $catColors[$notif->categorie] ?? 'bg-gray-100 text-gray-600' }} rounded-full">{{ $notif->categorie }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if ($notif->lue)
                            <span class="text-xs text-muted"><i class="fas fa-check-circle text-success mr-1"></i> Lu</span>
                        @else
                            <span class="text-xs text-accent font-bold"><i class="fas fa-circle text-[6px] mr-1"></i> Nouveau</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-xs text-muted">{{ $notif->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center text-muted">
                        <i class="fas fa-bell-slash text-4xl mb-4"></i>
                        <p class="font-bold">Aucune notification</p>
                        <p class="text-sm">Vous n'avez pas encore reçu de notifications.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
