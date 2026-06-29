<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Culte extends Model
{
    protected $fillable = [
        'date',
        'type',
        'theme',
        'orateur',
        'hommes',
        'femmes',
        'enfants',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'hommes' => 'integer',
            'femmes' => 'integer',
            'enfants' => 'integer',
        ];
    }

    public function getTotalAttribute(): int
    {
        return $this->hommes + $this->femmes + $this->enfants;
    }
}
