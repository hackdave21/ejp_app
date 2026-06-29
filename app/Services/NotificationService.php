<?php

namespace App\Services;

use App\Models\ActiviteRecente;
use App\Models\Notification;

class NotificationService
{
    public static function notify(int $userId, string $categorie, string $titre, string $message, ?string $lien = null): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'categorie' => $categorie,
            'titre' => $titre,
            'message' => $message,
            'lien' => $lien,
        ]);
    }

    public static function log(int $userId, string $type, string $action, ?string $cible = null): ActiviteRecente
    {
        return ActiviteRecente::create([
            'user_id' => $userId,
            'type' => $type,
            'action' => $action,
            'cible' => $cible,
        ]);
    }

    public static function notifyAndLog(int $userId, string $categorie, string $titre, string $message, ?string $lien, string $logType, string $logAction, ?string $logCible = null): void
    {
        static::notify($userId, $categorie, $titre, $message, $lien);
        static::log(auth()->id(), $logType, $logAction, $logCible);
    }
}
