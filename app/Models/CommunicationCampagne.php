<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunicationCampagne extends Model
{
    protected $table = 'communications_campagnes';

    protected $fillable = [
        'titre',
        'canal',
        'audience_cible',
        'contenu',
        'date_envoi',
        'statut',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'date_envoi' => 'datetime',
        ];
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
