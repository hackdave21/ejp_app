@extends('layouts.frontend')
@section('title', 'Formations')
@section('content')
<h1 class="text-2xl font-bold mb-6 dark:text-white">Formations</h1>

<div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm mb-6">
    <div class="flex items-center justify-between mb-2">
        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $suivis->filter()->count() }} sur {{ $modules->count() }} formations complétées</span>
        <span class="text-sm font-semibold dark:text-white">{{ $progress }}%</span>
    </div>
    <div class="bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
        <div class="bg-primary rounded-full h-2.5 transition-all" style="width: {{ $progress }}%"></div>
    </div>
</div>

@foreach (['fondements', 'leadership', 'ministeres'] as $cat)
    @php $catModules = $modules->where('categorie', $cat); @endphp
    @if ($catModules->count() > 0)
    <div class="mb-6">
        <h2 class="text-lg font-semibold mb-3 dark:text-white capitalize">{{ $cat }}</h2>
        <div class="space-y-2">
            @foreach ($catModules as $module)
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <a href="{{ route('membre.formations.show', $module) }}" class="flex items-center gap-3 flex-1">
                    <i class="fas {{ $module->icone ?? 'fa-book' }} text-primary"></i>
                    <span class="font-medium dark:text-white">{{ $module->titre }}</span>
                </a>
                @if (isset($suivis[$module->id]) && $suivis[$module->id])
                    <span class="text-green-600"><i class="fas fa-check-circle"></i></span>
                @else
                    <span class="text-gray-300 dark:text-gray-600"><i class="far fa-circle"></i></span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
@endforeach
@endsection
