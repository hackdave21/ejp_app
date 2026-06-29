<?php

use App\Http\Controllers\Admin\ChefController;
use App\Http\Controllers\Admin\CommunicationController;
use App\Http\Controllers\Admin\CulteController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DemandeProgressionController;
use App\Http\Controllers\Admin\EvenementController;
use App\Http\Controllers\Admin\FormationModuleController;
use App\Http\Controllers\Admin\GroupeController;
use App\Http\Controllers\Admin\MembreController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ParametreController;
use App\Http\Controllers\Admin\ReunionController;
use App\Http\Controllers\Admin\SuggestionController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Route publique
Route::get('/', function () {
    return view('welcome');
})->name('accueil');

// Authentification
Route::get('/login', [LoginController::class, 'showMembreLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin login
Route::get('/admin/login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'authenticate']);

// Chef login
Route::get('/chef/login', [LoginController::class, 'showChefLoginForm'])->name('chef.login');
Route::post('/chef/login', [LoginController::class, 'authenticate']);

// Routes protégées Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/membres', [MembreController::class, 'index'])->name('membres.index');
    Route::post('/membres', [MembreController::class, 'store'])->name('membres.store');
    Route::get('/membres/{membre}', [MembreController::class, 'show'])->name('membres.show');
    Route::put('/membres/{membre}', [MembreController::class, 'update'])->name('membres.update');
    Route::delete('/membres/{membre}', [MembreController::class, 'destroy'])->name('membres.destroy');

    Route::get('/chefs', [ChefController::class, 'index'])->name('chefs.index');
    Route::post('/chefs', [ChefController::class, 'store'])->name('chefs.store');
    Route::put('/chefs/{chef}', [ChefController::class, 'update'])->name('chefs.update');
    Route::delete('/chefs/{chef}', [ChefController::class, 'destroy'])->name('chefs.destroy');

    Route::get('/groupes', [GroupeController::class, 'index'])->name('groupes.index');
    Route::post('/groupes', [GroupeController::class, 'store'])->name('groupes.store');
    Route::put('/groupes/{groupe}', [GroupeController::class, 'update'])->name('groupes.update');
    Route::delete('/groupes/{groupe}', [GroupeController::class, 'destroy'])->name('groupes.destroy');

    Route::get('/formations', [FormationModuleController::class, 'index'])->name('formations.index');
    Route::post('/formations', [FormationModuleController::class, 'store'])->name('formations.store');
    Route::put('/formations/{formation}', [FormationModuleController::class, 'update'])->name('formations.update');
    Route::delete('/formations/{formation}', [FormationModuleController::class, 'destroy'])->name('formations.destroy');

    Route::get('/evenements', [EvenementController::class, 'index'])->name('evenements.index');
    Route::post('/evenements', [EvenementController::class, 'store'])->name('evenements.store');
    Route::get('/evenements/{evenement}', [EvenementController::class, 'show'])->name('evenements.show');
    Route::put('/evenements/{evenement}', [EvenementController::class, 'update'])->name('evenements.update');
    Route::delete('/evenements/{evenement}', [EvenementController::class, 'destroy'])->name('evenements.destroy');

    Route::get('/cultes', [CulteController::class, 'index'])->name('cultes.index');
    Route::post('/cultes', [CulteController::class, 'store'])->name('cultes.store');
    Route::put('/cultes/{culte}/presence', [CulteController::class, 'updatePresence'])->name('cultes.presence');

    Route::get('/reunions', [ReunionController::class, 'index'])->name('reunions.index');
    Route::post('/reunions', [ReunionController::class, 'store'])->name('reunions.store');
    Route::get('/reunions/{reunion}', [ReunionController::class, 'show'])->name('reunions.show');
    Route::put('/reunions/{reunion}', [ReunionController::class, 'update'])->name('reunions.update');
    Route::delete('/reunions/{reunion}', [ReunionController::class, 'destroy'])->name('reunions.destroy');
    Route::put('/reunions/{reunion}/archiver', [ReunionController::class, 'archiver'])->name('reunions.archiver');

    Route::get('/communications', [CommunicationController::class, 'index'])->name('communications.index');
    Route::post('/communications', [CommunicationController::class, 'store'])->name('communications.store');
    Route::put('/communications/{communication}', [CommunicationController::class, 'update'])->name('communications.update');
    Route::delete('/communications/{communication}', [CommunicationController::class, 'destroy'])->name('communications.destroy');

    Route::get('/suggestions', [SuggestionController::class, 'index'])->name('suggestions.index');
    Route::put('/suggestions/{suggestion}/statut', [SuggestionController::class, 'updateStatut'])->name('suggestions.statut');
    Route::delete('/suggestions/{suggestion}', [SuggestionController::class, 'destroy'])->name('suggestions.destroy');

    Route::get('/demandes-progression', [DemandeProgressionController::class, 'index'])->name('demandes.index');
    Route::put('/demandes-progression/{demande}/approuver', [DemandeProgressionController::class, 'approuver'])->name('demandes.approuver');
    Route::put('/demandes-progression/{demande}/refuser', [DemandeProgressionController::class, 'refuser'])->name('demandes.refuser');

    Route::get('/parametres', [ParametreController::class, 'index'])->name('parametres.index');
    Route::put('/parametres', [ParametreController::class, 'update'])->name('parametres.update');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications', [NotificationController::class, 'send'])->name('notifications.send');
});
