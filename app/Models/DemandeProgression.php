<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemandeProgression extends Model
{
    protected $table = 'demandes_progression';

    protected $fillable = [
        'membre_id',
        'from_level',
        'to_level',
        'formations_score',
        'assiduite_score',
        'anciennete',
        'service_sessions',
        'statut',
        'motif_refus',
        'traite_par_id',
        'date_traitement',
    ];

    protected function casts(): array
    {
        return [
            'date_traitement' => 'datetime',
        ];
    }

    public function membre(): BelongsTo
    {
        return $this->belongsTo(User::class, 'membre_id');
    }

    public function traitePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'traite_par_id');
    }
}
