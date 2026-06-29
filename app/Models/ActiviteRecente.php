<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActiviteRecente extends Model
{
    protected $table = 'activite_recente';

    protected $fillable = [
        'user_id',
        'type',
        'cible',
        'action',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
