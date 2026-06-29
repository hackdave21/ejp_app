<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormationSuivi extends Model
{
    protected $table = 'formations_suivi';

    protected $fillable = [
        'user_id',
        'module_id',
        'vu',
    ];

    protected function casts(): array
    {
        return [
            'vu' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(FormationModule::class, 'module_id');
    }
}
