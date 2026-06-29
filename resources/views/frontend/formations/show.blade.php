@extends('layouts.frontend')
@section('title', $module->titre)
@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm">
    <a href="{{ route('membre.formations.index') }}" class="text-sm text-primary hover:underline mb-4 inline-block">&larr; Retour aux formations</a>
    <h1 class="text-2xl font-bold mb-4 dark:text-white">{{ $module->titre }}</h1>
    <p class="text-gray-500 dark:text-gray-400 mb-4">{{ $module->description }}</p>
    @if ($module->video_url)
    <div class="aspect-video mb-4">
        <iframe src="{{ $module->video_url }}" class="w-full h-full rounded-lg" frameborder="0" allowfullscreen></iframe>
    </div>
    @endif
    <form method="POST" action="{{ route('membre.formations.markSeen', $module) }}">
        @csrf
        @if ($suivi->vu)
            <span class="px-4 py-2 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 rounded-lg"><i class="fas fa-check mr-2"></i>Déjà vu</span>
        @else
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">Marquer comme vu</button>
        @endif
    </form>
</div>
@endsection
