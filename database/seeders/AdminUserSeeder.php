<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nom' => 'Admin',
            'prenom' => 'EJP',
            'telephone' => '+225 00 00 00 00',
            'email' => 'admin@ejp.ci',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'statut' => 'pilier',
            'date_entree' => now(),
        ]);
    }
}
