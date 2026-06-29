<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\FormationModule;
use App\Models\FormationSuivi;

class FormationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $modules = FormationModule::where('statut', true)->orderBy('ordre')->get();
        $suivis = FormationSuivi::where('user_id', $user->id)->pluck('vu', 'module_id');

        $progress = $modules->count() > 0
            ? round($suivis->filter()->count() / $modules->count() * 100)
            : 0;

        return view('frontend.formations.index', compact('modules', 'suivis', 'progress'));
    }

    public function show(FormationModule $module)
    {
        $user = auth()->user();
        $suivi = FormationSuivi::firstOrCreate(
            ['user_id' => $user->id, 'module_id' => $module->id]
        );

        return view('frontend.formations.show', compact('module', 'suivi'));
    }

    public function markAsSeen(FormationModule $module)
    {
        $user = auth()->user();

        FormationSuivi::updateOrCreate(
            ['user_id' => $user->id, 'module_id' => $module->id],
            ['vu' => true]
        );

        return back()->with('success', 'Module marqué comme vu.');
    }
}
