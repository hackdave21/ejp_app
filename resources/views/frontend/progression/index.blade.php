@extends('layouts.frontend')
@section('title', 'Ma Progression')
@section('content')
<h1 class="text-2xl font-bold mb-6 dark:text-white">Ma Progression</h1>

@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg">{{ session('error') }}</div>
@endif

<div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm mb-6">
    <h2 class="text-lg font-semibold mb-4 dark:text-white">Parcours</h2>
    <div class="flex items-center justify-between">
        @foreach ($levels as $key => $level)
            <div class="text-center flex-1">
                <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center mb-1
                    {{ $currentOrder >= $level['order'] ? 'bg-primary text-white' : 'bg-gray-200 dark:bg-gray-600 text-gray-400' }}">
                    <i class="fas {{ $level['icon'] }}"></i>
                </div>
                <p class="text-xs {{ $currentOrder >= $level['order'] ? 'text-primary font-semibold' : 'text-gray-400' }}">{{ $level['label'] }}</p>
            </div>
            @if (!$loop->last)
                <div class="flex-1 h-0.5 {{ $currentOrder > $level['order'] ? 'bg-primary' : 'bg-gray-200 dark:bg-gray-600' }}"></div>
            @endif
        @endforeach
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <h3 class="font-semibold mb-4 dark:text-white">Formations suivies</h3>
        <div class="mb-3">
            <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-500 dark:text-gray-400">Progression</span>
                <span class="dark:text-white">{{ $formations->where('vu', true)->count() }}/{{ $allModulesCount }}</span>
            </div>
            <div class="bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                <div class="bg-primary rounded-full h-2" style="width: {{ $formationsProgress }}%"></div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold dark:text-white">Demander une progression</h3>
        </div>
        @if ($nextLevel)
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                Vous êtes actuellement <strong>{{ $levels[$user->statut]['label'] }}</strong>.
                Prochain niveau : <strong>{{ $nextLevel['label'] }}</strong>
            </p>
            @if ($formationsProgress >= 70)
                <form method="POST" action="{{ route('membre.progression.demander') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                        Demander la progression vers {{ $nextLevel['label'] }}
                    </button>
                </form>
            @else
                <p class="text-sm text-yellow-600 dark:text-yellow-400">Complétez au moins 70% des formations pour demander une progression.</p>
            @endif
        @else
            <p class="text-green-600 dark:text-green-400">Félicitations ! Vous avez atteint le niveau maximum.</p>
        @endif
    </div>
</div>

@if ($demandes->count() > 0)
<div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="p-4 border-b border-gray-100 dark:border-gray-700">
        <h3 class="font-semibold dark:text-white">Historique des demandes</h3>
    </div>
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold">De → Vers</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Date</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Statut</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach ($demandes as $demande)
            <tr>
                <td class="px-4 py-3 dark:text-white">{{ $demande->from_level }} → {{ $demande->to_level }}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $demande->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs rounded-full
                        @if($demande->statut === 'en_attente') bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30
                        @elseif($demande->statut === 'approuvee') bg-green-100 text-green-600 dark:bg-green-900/30
                        @else bg-red-100 text-red-600 dark:bg-red-900/30 @endif">
                        {{ $demande->statut }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection
