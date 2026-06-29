<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'email',
        'photo',
        'password',
        'role',
        'statut',
        'chef_responsable_id',
        'date_naissance',
        'date_entree',
        '2fa_enabled',
        'derniere_connexion',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'date_naissance' => 'date',
            'date_entree' => 'date',
            'derniere_connexion' => 'datetime',
            '2fa_enabled' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function chefResponsable(): BelongsTo
    {
        return $this->belongsTo(Chef::class, 'chef_responsable_id');
    }

    public function chef(): HasOne
    {
        return $this->hasOne(Chef::class, 'user_id');
    }

    public function groupes(): BelongsToMany
    {
        return $this->belongsToMany(Groupe::class, 'groupe_membre', 'membre_id', 'groupe_id')
            ->withPivot('date_affectation')
            ->withTimestamps();
    }

    public function formationsSuivi(): HasMany
    {
        return $this->hasMany(FormationSuivi::class, 'user_id');
    }

    public function demandesProgression(): HasMany
    {
        return $this->hasMany(DemandeProgression::class, 'membre_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function activitesRecentes(): HasMany
    {
        return $this->hasMany(ActiviteRecente::class, 'user_id');
    }

    public function suggestions(): HasMany
    {
        return $this->hasMany(Suggestion::class, 'user_id');
    }

    public function reunions(): HasMany
    {
        return $this->hasMany(Reunion::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isChef(): bool
    {
        return $this->role === 'chef';
    }

    public function isMembre(): bool
    {
        return $this->role === 'membre';
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->prenom} {$this->nom}");
    }
}
