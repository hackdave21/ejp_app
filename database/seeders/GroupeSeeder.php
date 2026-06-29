<?php

namespace Database\Seeders;

use App\Models\Chef;
use App\Models\Groupe;
use App\Models\User;
use Illuminate\Database\Seeder;

class GroupeSeeder extends Seeder
{
    public function run(): void
    {
        $chefs = Chef::all();
        $groupes = ['Gédéon', 'David', 'Esther', 'Josué', 'Ruth'];

        foreach ($groupes as $i => $nom) {
            $chef = $chefs[$i % $chefs->count()];

            $groupe = Groupe::create([
                'nom' => "Groupe $nom",
                'chef_id' => $chef->id,
                'capacite_max' => 50,
            ]);

            $membres = User::where('role', 'membre')
                ->skip($i * 4)
                ->take(4)
                ->get();

            foreach ($membres as $membre) {
                $groupe->membres()->attach($membre->id, [
                    'date_affectation' => now()->subMonths(rand(1, 12)),
                ]);

                if (!$membre->chef_responsable_id) {
                    $membre->update(['chef_responsable_id' => $chef->id]);
                }
            }
        }
    }
}
