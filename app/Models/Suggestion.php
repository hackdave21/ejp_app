<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Suggestion extends Model
{
    protected $fillable = [
        'categorie',
        'nom',
        'contenu',
        'statut',
        'user_id',
        'lu_par_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function luPar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lu_par_id');
    }
}
