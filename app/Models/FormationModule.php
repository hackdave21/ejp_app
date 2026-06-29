<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormationModule extends Model
{
    protected $table = 'formations_modules';

    protected $fillable = [
        'titre',
        'categorie',
        'icone',
        'ordre',
        'description',
        'video_url',
        'duree',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'ordre' => 'integer',
            'statut' => 'boolean',
        ];
    }

    public function suivis(): HasMany
    {
        return $this->hasMany(FormationSuivi::class, 'module_id');
    }
}
