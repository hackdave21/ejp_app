<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class MembreSeeder extends Seeder
{
    public function run(): void
    {
        $statuts = ['nouveau_membre', 'star', 'pilote', 'pilier'];

        for ($i = 1; $i <= 20; $i++) {
            User::factory()->create([
                'statut' => $statuts[array_rand($statuts)],
                'date_entree' => now()->subMonths(rand(1, 36)),
            ]);
        }
    }
}
