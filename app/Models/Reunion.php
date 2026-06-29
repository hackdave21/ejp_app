<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reunion extends Model
{
    protected $fillable = [
        'titre',
        'type',
        'date',
        'contenu',
        'participants',
        'sujets_priere',
        'statut',
        'signature',
        'user_id',
        'groupe_id',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'participants' => 'json',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function groupe(): BelongsTo
    {
        return $this->belongsTo(Groupe::class, 'groupe_id');
    }
}
