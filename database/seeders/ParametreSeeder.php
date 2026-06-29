<?php

namespace Database\Seeders;

use App\Models\Parametre;
use Illuminate\Database\Seeder;

class ParametreSeeder extends Seeder
{
    public function run(): void
    {
        $params = [
            ['cle' => 'app_nom', 'valeur' => 'EJP Portail Membres', 'type' => 'string'],
            ['cle' => 'email_contact', 'valeur' => 'contact@ejp.ci', 'type' => 'string'],
            ['cle' => 'whatsapp_assistance', 'valeur' => '+225 00 00 00 00 00', 'type' => 'string'],
            ['cle' => 'inscriptions_ouvertes', 'valeur' => 'true', 'type' => 'boolean'],
            ['cle' => 'mode_maintenance', 'valeur' => 'false', 'type' => 'boolean'],
            ['cle' => 'caracteres_speciaux_obligatoires', 'valeur' => 'true', 'type' => 'boolean'],
            ['cle' => '2fa_chefs', 'valeur' => 'false', 'type' => 'boolean'],
        ];

        foreach ($params as $param) {
            Parametre::create($param);
        }
    }
}
