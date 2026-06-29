<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Groupe extends Model
{
    protected $fillable = [
        'nom',
        'chef_id',
        'capacite_max',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'capacite_max' => 'integer',
        ];
    }

    public function chef(): BelongsTo
    {
        return $this->belongsTo(Chef::class, 'chef_id');
    }

    public function membres(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'groupe_membre', 'groupe_id', 'membre_id')
            ->withPivot('date_affectation')
            ->withTimestamps();
    }

    public function reunions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Reunion::class, 'groupe_id');
    }
}
