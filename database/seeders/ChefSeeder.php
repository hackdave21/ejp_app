<?php

namespace Database\Seeders;

use App\Models\Chef;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChefSeeder extends Seeder
{
    public function run(): void
    {
        $chefs = [
            ['nom' => 'Amos', 'prenom' => 'Kouadio'],
            ['nom' => 'Koffi', 'prenom' => 'Paul'],
            ['nom' => 'Koné', 'prenom' => 'Marie'],
        ];

        foreach ($chefs as $data) {
            $user = User::create([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'telephone' => '+225 ' . fake()->unique()->numerify('## ## ## ##'),
                'email' => strtolower($data['prenom'] . '.' . $data['nom']) . '@ejp.ci',
                'password' => bcrypt('chef123'),
                'role' => 'chef',
                'statut' => 'pilier',
                'date_entree' => now()->subMonths(rand(6, 24)),
            ]);

            Chef::create([
                'user_id' => $user->id,
                'role' => 'chef_de_groupe',
                'telephone' => $user->telephone,
            ]);
        }
    }
}
