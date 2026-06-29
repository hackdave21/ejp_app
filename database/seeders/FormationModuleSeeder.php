<?php

namespace Database\Seeders;

use App\Models\FormationModule;
use Illuminate\Database\Seeder;

class FormationModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            ['titre' => 'Vie en Christ', 'categorie' => 'fondements', 'icone' => 'fa-bible', 'ordre' => 1],
            ['titre' => 'La Prière', 'categorie' => 'fondements', 'icone' => 'fa-pray', 'ordre' => 2],
            ['titre' => 'La Parole de Dieu', 'categorie' => 'fondements', 'icone' => 'fa-book', 'ordre' => 3],
            ['titre' => 'Le Leadership Serviteur', 'categorie' => 'leadership', 'icone' => 'fa-users', 'ordre' => 4],
            ['titre' => 'Gestion d\'Équipe', 'categorie' => 'leadership', 'icone' => 'fa-people-group', 'ordre' => 5],
            ['titre' => 'Prise de Parole', 'categorie' => 'leadership', 'icone' => 'fa-microphone', 'ordre' => 6],
            ['titre' => 'Évangélisation', 'categorie' => 'ministeres', 'icone' => 'fa-cross', 'ordre' => 7],
            ['titre' => 'Enseignement', 'categorie' => 'ministeres', 'icone' => 'fa-chalkboard', 'ordre' => 8],
            ['titre' => 'Pastorale', 'categorie' => 'ministeres', 'icone' => 'fa-hand-holding-heart', 'ordre' => 9],
            ['titre' => 'Mission et Discipolat', 'categorie' => 'ministeres', 'icone' => 'fa-globe', 'ordre' => 10],
        ];

        foreach ($modules as $module) {
            FormationModule::create($module);
        }
    }
}
