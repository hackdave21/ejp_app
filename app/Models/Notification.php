<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'categorie',
        'titre',
        'message',
        'lue',
        'lien',
        'date_lue',
    ];

    protected function casts(): array
    {
        return [
            'lue' => 'boolean',
            'date_lue' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
