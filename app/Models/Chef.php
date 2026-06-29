<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chef extends Model
{
    protected $fillable = [
        'user_id',
        'role',
        'telephone',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'statut' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function membres(): HasMany
    {
        return $this->hasMany(User::class, 'chef_responsable_id');
    }

    public function groupes(): HasMany
    {
        return $this->hasMany(Groupe::class, 'chef_id');
    }
}
