<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evenement extends Model
{
    protected $fillable = [
        'titre',
        'type',
        'capacite_max',
        'date_debut',
        'date_fin',
        'lieu',
        'description',
        'image_couverture',
        'nombre_participants',
        'rapport',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'datetime',
            'date_fin' => 'datetime',
            'capacite_max' => 'integer',
            'nombre_participants' => 'integer',
        ];
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
