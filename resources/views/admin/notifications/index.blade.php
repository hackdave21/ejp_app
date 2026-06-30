@extends('layouts.admin')
@section('title', 'Gestion des Notifications')
@section('page_title', 'Notifications')
@section('content')
@if (session('success'))
    <div class="mb-6 p-4 bg-success/10 border border-success/30 text-success rounded-xl flex items-center gap-3 text-sm font-body">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-muted font-body">Gérez et envoyez des notifications aux membres.</p>
    <button onclick="openModal('sendNotifModal')" class="px-5 py-2.5 bg-accent text-marine font-bold text-sm rounded-xl hover:bg-accent/90 transition-all hover:scale-105 flex items-center gap-2 shadow-lg shadow-accent/20">
        <i class="fas fa-paper-plane"></i> Envoyer
    </button>
</div>

<div class="bg-surface border border-border rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-muted/5 border-b border-border">
                    <th class="px-5 py-4 text-left text-[11px] font-bold text-muted uppercase tracking-widest">Destinataire</th>
                    <th class="px-5 py-4 text-left text-[11px] font-bold text-muted uppercase tracking-widest">Titre</th>
                    <th class="px-5 py-4 text-left text-[11px] font-bold text-muted uppercase tracking-widest">Catégorie</th>
                    <th class="px-5 py-4 text-center text-[11px] font-bold text-muted uppercase tracking-widest">Statut</th>
                    <th class="px-5 py-4 text-left text-[11px] font-bold text-muted uppercase tracking-widest">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($notifications as $notif)
                <tr class="hover:bg-white/5 transition-colors {{ !$notif->lue ? 'bg-accent/[0.02]' : '' }}">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-primary/5 border border-border flex items-center justify-center text-primary font-bold text-xs">
                                {{ strtoupper(substr($notif->user->prenom ?? '?', 0, 1)) }}{{ strtoupper(substr($notif->user->nom ?? '', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-primary text-sm">{{ $notif->user->full_name }}</p>
                                <p class="text-[11px] text-muted font-mono">{{ $notif->user->role }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <p class="font-bold text-primary text-sm">{{ $notif->titre }}</p>
                        @if ($notif->message)
                        <p class="text-xs text-muted mt-0.5 line-clamp-1">{{ Str::limit($notif->message, 60) }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[11px] font-mono rounded-full 
                            @switch($notif->categorie)
                                @case('systeme') bg-success/10 text-success border border-success/30 @break
                                @case('evenements') bg-blue-500/10 text-blue-500 border border-blue-500/30 @break
                                @case('formations') bg-accent/10 text-accent border border-accent/30 @break
                                @case('progression') bg-purple-500/10 text-purple-500 border border-purple-500/30 @break
                                @default bg-muted/10 text-muted border border-border
                            @endswitch">
                            <i class="fas 
                                @switch($notif->categorie)
                                    @case('systeme') fa-cog @break
                                    @case('evenements') fa-calendar @break
                                    @case('formations') fa-graduation-cap @break
                                    @case('progression') fa-arrow-up @break
                                    @default fa-bell
                                @endswitch
                            "></i>
                            {{ ucfirst($notif->categorie) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        @if ($notif->lue)
                            <span class="text-success text-sm" title="Lue"><i class="fas fa-check-circle"></i></span>
                        @else
                            <span class="text-accent text-sm" title="Non lue"><i class="fas fa-circle text-[8px]"></i></span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-sm text-muted font-body">{{ $notif->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-20 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-16 h-16 rounded-full bg-surface border border-border flex items-center justify-center">
                                <i class="fas fa-bell-slash text-2xl text-muted"></i>
                            </div>
                            <p class="text-muted font-body text-sm">Aucune notification pour le moment.</p>
                            <button onclick="openModal('sendNotifModal')" class="text-accent text-sm font-bold hover:underline">Envoyer la première notification</button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="sendNotifModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-surface border border-border rounded-2xl p-6 w-full max-w-lg mx-4 shadow-2xl animate-fade-in-up">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-primary flex items-center gap-2">
                <i class="fas fa-paper-plane text-accent"></i> Envoyer une notification
            </h3>
            <button onclick="closeModal('sendNotifModal')" class="w-8 h-8 rounded-lg bg-surface text-muted hover:text-text hover:bg-white/10 transition-colors flex items-center justify-center">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.notifications.send') }}" class="space-y-4 font-body">
            @csrf
            <div>
                <label class="block text-sm font-bold text-primary mb-1.5">Destinataire</label>
                <select name="user_id" required class="w-full px-4 py-2.5 border border-border rounded-xl bg-bg text-text focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/30 transition-colors text-sm">
                    @foreach (\App\Models\User::whereIn('role', ['membre', 'chef'])->get() as $user)
                        <option value="{{ $user->id }}">{{ $user->full_name }} ({{ $user->role }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-primary mb-1.5">Catégorie</label>
                <select name="categorie" class="w-full px-4 py-2.5 border border-border rounded-xl bg-bg text-text focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/30 transition-colors text-sm">
                    <option value="systeme">Système</option>
                    <option value="evenements">Événements</option>
                    <option value="formations">Formations</option>
                    <option value="progression">Progression</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-primary mb-1.5">Titre</label>
                <input type="text" name="titre" required class="w-full px-4 py-2.5 border border-border rounded-xl bg-bg text-text focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/30 transition-colors text-sm" placeholder="Titre de la notification">
            </div>
            <div>
                <label class="block text-sm font-bold text-primary mb-1.5">Message</label>
                <textarea name="message" rows="3" required class="w-full px-4 py-2.5 border border-border rounded-xl bg-bg text-text focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/30 transition-colors text-sm" placeholder="Contenu du message..."></textarea>
            </div>
            <div>
                <label class="block text-sm font-bold text-primary mb-1.5">Lien (optionnel)</label>
                <input type="text" name="lien" class="w-full px-4 py-2.5 border border-border rounded-xl bg-bg text-text focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/30 transition-colors text-sm" placeholder="/chemin/vers/page">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal('sendNotifModal')" class="px-5 py-2.5 text-sm font-bold text-muted hover:text-text hover:bg-surface border border-border rounded-lg transition-colors">Annuler</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold bg-accent text-marine rounded-xl hover:bg-accent/90 transition-all hover:scale-105 flex items-center gap-2">
                    <i class="fas fa-paper-plane"></i> Envoyer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
</script>
@endpush