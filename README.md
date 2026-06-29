# EJP Portail Membres

Application Laravel 12 de gestion d'église — portail membres avec rôles **Admin**, **Chef de groupe** et **Membre**.

---

## Analyse du Template (`public/ejptemplate/`)

### Structure des rôles

| Rôle | Accès | Pages |
|------|-------|-------|
| **Admin** | Tout | Dashboard, Membres, Formations, Événements, Cultes, Communications, Chefs & Groupes, Réunions, Suggestions, Paramètres |
| **Chef** | Son groupe uniquement | Dashboard, Membres du groupe, Réunions (PV), Demandes de progression |
| **Membre** | Son propre compte | Dashboard, Formations, Événements, Progression, Notifications, Compte, Suggestions |

---

### Tables et colonnes identifiées

#### 1. `users` (Membres / Utilisateurs)

| Champ | Type | Contraintes |
|-------|------|-------------|
| `id` | bigIncrements | PK |
| `nom` | string(100) | required |
| `prenom` | string(100) | required |
| `telephone` | string(20) | required, unique |
| `email` | string(100) | nullable, unique |
| `photo` | string(255) | nullable |
| `password` | string(255) | required |
| `remember_token` | string(100) | nullable |
| `role` | enum('admin','chef','membre') | default 'membre' |
| `statut` | enum('nouveau_membre','star','pilote','pilier','missionnaire') | default 'nouveau_membre' |
| `chef_responsable_id` | foreignId → chefs.id | nullable |
| `date_naissance` | date | nullable |
| `date_entree` | date | nullable |
| `email_verified_at` | timestamp | nullable |
| `2fa_enabled` | boolean | default false |
| `derniere_connexion` | datetime | nullable |
| `timestamps` | — | — |
| `softDeletes` | — | — |

#### 2. `chefs` (Responsables de groupes)

| Champ | Type | Contraintes |
|-------|------|-------------|
| `id` | bigIncrements | PK |
| `user_id` | foreignId → users.id | unique (chaque chef est un user) |
| `role` | enum('chef_de_groupe','superviseur','pasteur') | default 'chef_de_groupe' |
| `telephone` | string(20) | required |
| `statut` | boolean | default true |
| `timestamps` | — | — |

#### 3. `groupes` (Groupes)

| Champ | Type | Contraintes |
|-------|------|-------------|
| `id` | bigIncrements | PK |
| `nom` | string(100) | required |
| `chef_id` | foreignId → chefs.id | required |
| `capacite_max` | integer | default 50 |
| `description` | text | nullable |
| `timestamps` | — | — |

#### 4. `groupe_membre` (Pivot : groupes ↔ membres)

| Champ | Type | Contraintes |
|-------|------|-------------|
| `id` | bigIncrements | PK |
| `groupe_id` | foreignId → groupes.id | FK |
| `membre_id` | foreignId → users.id | FK |
| `date_affectation` | date | nullable |
| `timestamps` | — | — |

#### 5. `formations_modules` (Modules de formation)

| Champ | Type | Contraintes |
|-------|------|-------------|
| `id` | bigIncrements | PK |
| `titre` | string(255) | required |
| `categorie` | enum('fondements','leadership','ministeres') | required |
| `icone` | string(100) | nullable |
| `ordre` | integer | default 0 |
| `description` | text | nullable |
| `video_url` | string(255) | nullable |
| `duree` | string(50) | nullable |
| `statut` | boolean | default true |
| `timestamps` | — | — |

#### 6. `formations_suivi` (Suivi des formations par membre)

| Champ | Type | Contraintes |
|-------|------|-------------|
| `id` | bigIncrements | PK |
| `user_id` | foreignId → users.id | FK |
| `module_id` | foreignId → formations_modules.id | FK |
| `vu` | boolean | default false |
| `timestamps` | — | — |

#### 7. `evenements` (Événements)

| Champ | Type | Contraintes |
|-------|------|-------------|
| `id` | bigIncrements | PK |
| `titre` | string(255) | required |
| `type` | enum('concert','retraite_spirituelle','evangelisation','seminaire') | required |
| `capacite_max` | integer | default 0 (0 = illimité) |
| `date_debut` | datetime | required |
| `date_fin` | datetime | required |
| `lieu` | string(255) | required |
| `description` | text | nullable |
| `image_couverture` | string(255) | nullable |
| `statut` | enum('a_venir','en_cours','termine') | computed via scope |
| `nombre_participants` | integer | default 0 |
| `rapport` | string(255) | nullable |
| `user_id` | foreignId → users.id | nullable (créateur) |
| `timestamps` | — | — |

#### 8. `cultes` (Cultes / Services)

| Champ | Type | Contraintes |
|-------|------|-------------|
| `id` | bigIncrements | PK |
| `date` | date | required |
| `type` | string(100) | required (ex: "Dimanche Culte") |
| `theme` | string(255) | required |
| `orateur` | string(255) | required |
| `hommes` | integer | default 0 |
| `femmes` | integer | default 0 |
| `enfants` | integer | default 0 |
| `total` | integer | computed (hommes+femmes+enfants) |
| `timestamps` | — | — |

#### 9. `reunions` (Procès-verbaux de réunions)

| Champ | Type | Contraintes |
|-------|------|-------------|
| `id` | bigIncrements | PK |
| `titre` | string(255) | required |
| `type` | enum('generale','coordination','urgence') | default 'generale' |
| `date` | date | required |
| `contenu` | text | required |
| `participants` | json | nullable |
| `sujets_priere` | text | nullable |
| `statut` | enum('brouillon','archive') | default 'brouillon' |
| `signature` | text | nullable |
| `user_id` | foreignId → users.id | FK (soumis par) |
| `groupe_id` | foreignId → groupes.id | nullable |
| `timestamps` | — | — |

#### 10. `communications_campagnes` (Campagnes de communication)

| Champ | Type | Contraintes |
|-------|------|-------------|
| `id` | bigIncrements | PK |
| `titre` | string(255) | required |
| `canal` | enum('email','notification_push','annonce_in_app') | required |
| `audience_cible` | enum('tous_membres','tous_chefs','groupe_specifique') | required |
| `contenu` | text | required |
| `date_envoi` | datetime | nullable (programmé) |
| `statut` | enum('brouillon','envoye','programme') | default 'brouillon' |
| `user_id` | foreignId → users.id | nullable (créateur) |
| `timestamps` | — | — |

#### 11. `suggestions` (Suggestions / Feedback)

| Champ | Type | Contraintes |
|-------|------|-------------|
| `id` | bigIncrements | PK |
| `categorie` | enum('eglise','plateforme') | required |
| `nom` | string(100) | nullable (anonyme) |
| `contenu` | text | required |
| `statut` | enum('nouveau','lu','traite') | default 'nouveau' |
| `user_id` | foreignId → users.id | nullable |
| `lu_par_id` | foreignId → users.id | nullable |
| `timestamps` | — | — |

#### 12. `demandes_progression` (Demandes de progression)

| Champ | Type | Contraintes |
|-------|------|-------------|
| `id` | bigIncrements | PK |
| `membre_id` | foreignId → users.id | FK |
| `from_level` | enum(...statuts) | required |
| `to_level` | enum(...statuts) | required |
| `formations_score` | string(50) | nullable |
| `assiduite_score` | string(50) | nullable |
| `anciennete` | string(50) | nullable |
| `service_sessions` | string(50) | nullable |
| `statut` | enum('en_attente','approuvee','refusee') | default 'en_attente' |
| `motif_refus` | text | nullable |
| `traite_par_id` | foreignId → users.id | nullable |
| `date_traitement` | datetime | nullable |
| `timestamps` | — | — |

#### 13. `notifications` (Notifications)

| Champ | Type | Contraintes |
|-------|------|-------------|
| `id` | bigIncrements | PK |
| `user_id` | foreignId → users.id | FK |
| `categorie` | enum('progression','evenements','formations','systeme') | required |
| `titre` | string(255) | required |
| `message` | text | required |
| `lue` | boolean | default false |
| `lien` | string(255) | nullable |
| `date_lue` | datetime | nullable |
| `timestamps` | — | — |

#### 14. `activite_recente` (Journal d'activité)

| Champ | Type | Contraintes |
|-------|------|-------------|
| `id` | bigIncrements | PK |
| `user_id` | foreignId → users.id | FK (acteur) |
| `type` | enum('demande_progression','validation','evenement_cree','membre_ajoute','suggestion') | required |
| `cible` | string(255) | nullable |
| `action` | string(255) | required |
| `timestamps` | — | — |

#### 15. `parametres` (Paramètres de l'application)

| Champ | Type | Contraintes |
|-------|------|-------------|
| `id` | bigIncrements | PK |
| `cle` | string(100) | unique |
| `valeur` | text | required |
| `type` | enum('string','boolean','integer','json') | default 'string' |
| `timestamps` | — | — |

---

## Relations entre les tables

```
users (membre)
  ├── belongsTo: chef_responsable (chefs)
  ├── belongsToMany: groupes (via groupe_membre)
  ├── hasMany: formations_suivi
  ├── hasMany: demandes_progression (en tant que membre)
  ├── hasMany: notifications
  ├── hasMany: activite_recente
  ├── hasMany: suggestions
  └── hasOne: chef (si rôle=chef)

chefs
  ├── belongsTo: user
  ├── hasMany: users (membres suivis)
  └── hasMany: groupes (groupes dirigés)

groupes
  ├── belongsTo: chef
  └── belongsToMany: users (via groupe_membre)

formations_modules
  └── hasMany: formations_suivi

formations_suivi
  ├── belongsTo: user
  └── belongsTo: formations_module

evenements
  └── belongsTo: user (créateur)

cultes
  (indépendant)

reunions
  ├── belongsTo: user (soumis par)
  └── belongsTo: groupe (optionnel)

communications_campagnes
  └── belongsTo: user (créateur)

suggestions
  └── belongsTo: user (soumis par, optionnel)

demandes_progression
  ├── belongsTo: user (membre)
  └── belongsTo: user (traité par)

notifications
  └── belongsTo: user

activite_recente
  └── belongsTo: user (acteur)
```

---

## Ordre de création (Migrations)

1. `create_users_table` (avec toutes les colonnes spécifiques à EJP)
2. `create_chefs_table`
3. `create_groupes_table`
4. `create_groupe_membre_table` (table pivot)
5. `create_formations_modules_table`
6. `create_formations_suivi_table`
7. `create_evenements_table`
8. `create_cultes_table`
9. `create_reunions_table`
10. `create_communications_campagnes_table`
11. `create_suggestions_table`
12. `create_demandes_progression_table`
13. `create_notifications_table`
14. `create_activite_recente_table`
15. `create_parametres_table`

---

## Ordre de création (Models)

1. `User` (étendu)
2. `Chef`
3. `Groupe`
4. `FormationModule`
5. `FormationSuivi`
6. `Evenement`
7. `Culte`
8. `Reunion`
9. `CommunicationCampagne`
10. `Suggestion`
11. `DemandeProgression`
12. `Notification`
13. `ActiviteRecente`
14. `Parametre`

---

## Ordre de création (Controllers)

### Admin Controllers

1. `Admin/AuthController` — Login admin
2. `Admin/DashboardController` — Dashboard admin (statistiques, KPIs)
3. `Admin/MembreController` — CRUD membres
4. `Admin/ChefController` — Gestion des chefs
5. `Admin/GroupeController` — Gestion des groupes
6. `Admin/FormationModuleController` — CRUD modules de formation
7. `Admin/EvenementController` — CRUD événements
8. `Admin/CulteController` — CRUD cultes + pointage présences
9. `Admin/ReunionController` — CRUD réunions
10. `Admin/CommunicationController` — Campagnes de communication
11. `Admin/SuggestionController` — Gestion des suggestions
12. `Admin/DemandeProgressionController` — Validation des progressions
13. `Admin/ParametreController` — Paramètres système
14. `Admin/NotificationController` — Envoi de notifications

### Chef Controllers

1. `Chef/AuthController` — Login chef
2. `Chef/DashboardController` — Dashboard chef
3. `Chef/MembreController` — Membres du groupe (lecture seule + suivi)
4. `Chef/ReunionController` — PV de réunions du groupe
5. `Chef/DemandeProgressionController` — Demandes de progression du groupe

### Frontend Controllers

1. `Frontend/AuthController` — Login/register membres
2. `Frontend/DashboardController` — Dashboard membre
3. `Frontend/FormationController` — Visualisation formations
4. `Frontend/EvenementController` — Visualisation événements
5. `Frontend/ProgressionController` — Suivi progression personnelle
6. `Frontend/NotificationController` — Notifications du membre
7. `Frontend/CompteController` — Gestion du compte (profil, mot de passe)
8. `Frontend/SuggestionController` — Soumission de suggestions

---

## Routes (web.php)

### Middleware par rôle

- `web`, `auth`, `role:admin` → prefix `admin/`
- `web`, `auth`, `role:chef` → prefix `chef/`
- `web`, `auth`, `role:membre` → prefix `membre/` (ou espace non préfixé)

### Routes Admin

```
GET  /admin/login                    Admin/AuthController@login
POST /admin/login                    Admin/AuthController@authenticate
POST /admin/logout                   Admin/AuthController@logout

GET  /admin/dashboard                Admin/DashboardController@index

GET  /admin/membres                  Admin/MembreController@index
POST /admin/membres                  Admin/MembreController@store
GET  /admin/membres/{id}             Admin/MembreController@show
PUT  /admin/membres/{id}             Admin/MembreController@update
DELETE /admin/membres/{id}           Admin/MembreController@destroy

GET  /admin/chefs                    Admin/ChefController@index
POST /admin/chefs                    Admin/ChefController@store
PUT  /admin/chefs/{id}               Admin/ChefController@update
DELETE /admin/chefs/{id}             Admin/ChefController@destroy

GET  /admin/groupes                  Admin/GroupeController@index
POST /admin/groupes                  Admin/GroupeController@store
PUT  /admin/groupes/{id}             Admin/GroupeController@update
DELETE /admin/groupes/{id}           Admin/GroupeController@destroy

GET  /admin/formations               Admin/FormationModuleController@index
POST /admin/formations               Admin/FormationModuleController@store
PUT  /admin/formations/{id}          Admin/FormationModuleController@update
DELETE /admin/formations/{id}        Admin/FormationModuleController@destroy

GET  /admin/evenements               Admin/EvenementController@index
POST /admin/evenements               Admin/EvenementController@store
GET  /admin/evenements/{id}          Admin/EvenementController@show
PUT  /admin/evenements/{id}          Admin/EvenementController@update
DELETE /admin/evenements/{id}        Admin/EvenementController@destroy

GET  /admin/cultes                   Admin/CulteController@index
POST /admin/cultes                   Admin/CulteController@store
PUT  /admin/cultes/{id}/presence     Admin/CulteController@updatePresence
GET  /admin/cultes/{id}/export       Admin/CulteController@exportPDF

GET  /admin/reunions                 Admin/ReunionController@index
POST /admin/reunions                 Admin/ReunionController@store
GET  /admin/reunions/{id}            Admin/ReunionController@show
PUT  /admin/reunions/{id}            Admin/ReunionController@update
DELETE /admin/reunions/{id}          Admin/ReunionController@destroy
PUT  /admin/reunions/{id}/archiver   Admin/ReunionController@archiver

GET  /admin/communications           Admin/CommunicationController@index
POST /admin/communications           Admin/CommunicationController@store
PUT  /admin/communications/{id}      Admin/CommunicationController@update
DELETE /admin/communications/{id}    Admin/CommunicationController@destroy

GET  /admin/suggestions              Admin/SuggestionController@index
PUT  /admin/suggestions/{id}/statut  Admin/SuggestionController@updateStatut
DELETE /admin/suggestions/{id}       Admin/SuggestionController@destroy

GET  /admin/demandes-progression     Admin/DemandeProgressionController@index
PUT  /admin/demandes-progression/{id}/approuver   Admin/DemandeProgressionController@approuver
PUT  /admin/demandes-progression/{id}/refuser     Admin/DemandeProgressionController@refuser

GET  /admin/parametres               Admin/ParametreController@index
PUT  /admin/parametres               Admin/ParametreController@update

GET  /admin/notifications            Admin/NotificationController@index
POST /admin/notifications            Admin/NotificationController@send
```

### Routes Chef

```
GET  /chef/login                     Chef/AuthController@login
POST /chef/login                     Chef/AuthController@authenticate
POST /chef/logout                    Chef/AuthController@logout

GET  /chef/dashboard                 Chef/DashboardController@index

GET  /chef/membres                   Chef/MembreController@index
GET  /chef/membres/{id}              Chef/MembreController@show

GET  /chef/reunions                  Chef/ReunionController@index
POST /chef/reunions                  Chef/ReunionController@store
GET  /chef/reunions/{id}             Chef/ReunionController@show
PUT  /chef/reunions/{id}             Chef/ReunionController@update
DELETE /chef/reunions/{id}           Chef/ReunionController@destroy
PUT  /chef/reunions/{id}/soumettre   Chef/ReunionController@soumettre

GET  /chef/demandes-progression      Chef/DemandeProgressionController@index
POST /chef/demandes-progression      Chef/DemandeProgressionController@store
PUT  /chef/demandes-progression/{id}/approuver  Chef/DemandeProgressionController@approuver
PUT  /chef/demandes-progression/{id}/refuser    Chef/DemandeProgressionController@refuser
```

### Routes Frontend (Membre)

```
GET  /login                          Frontend/AuthController@login
POST /login                          Frontend/AuthController@authenticate
GET  /register                       Frontend/AuthController@register
POST /register                       Frontend/AuthController@store
POST /logout                         Frontend/AuthController@logout
GET  /mot-de-passe-oublie            Frontend/AuthController@forgotPassword
POST /mot-de-passe-oublie            Frontend/AuthController@sendResetLink

GET  /dashboard                      Frontend/DashboardController@index

GET  /formations                     Frontend/FormationController@index
GET  /formations/{id}                Frontend/FormationController@show
POST /formations/{id}/marquer-vu     Frontend/FormationController@markAsSeen

GET  /evenements                     Frontend/EvenementController@index
GET  /evenements/{id}                Frontend/EvenementController@show

GET  /progression                    Frontend/ProgressionController@index
POST /progression/demander           Frontend/ProgressionController@demanderProgression

GET  /notifications                  Frontend/NotificationController@index
PUT  /notifications/{id}/lire        Frontend/NotificationController@markAsRead
PUT  /notifications/lire-toutes      Frontend/NotificationController@markAllAsRead
DELETE /notifications/{id}           Frontend/NotificationController@destroy

GET  /compte                         Frontend/CompteController@edit
PUT  /compte                         Frontend/CompteController@update
PUT  /compte/mot-de-passe            Frontend/CompteController@updatePassword
PUT  /compte/photo                   Frontend/CompteController@updatePhoto
PUT  /compte/preferences-notifications Frontend/CompteController@updateNotificationPrefs

POST /suggestions                    Frontend/SuggestionController@store
```

### Routes Landing Page (publiques)

```
GET  /                               LandingController@index
```

---

## Middleware personnalisé nécessaire

1. **CheckRole** — Vérifie que l'utilisateur connecté a le bon rôle (`admin`, `chef`, `membre`)
2. **ChefAccess** — Vérifie que le chef a accès au membre/groupe demandé (scope)
3. **MaintenanceMode** — Bascule selon le paramètre `mode_maintenance`

---

## Seeders

1. `AdminUserSeeder` — Crée l'admin par défaut
2. `ChefSeeder` — Crée des chefs de groupe
3. `MembreSeeder` — Crée des membres
4. `GroupeSeeder` — Crée des groupes et les associe aux chefs
5. `FormationModuleSeeder` — Crée les modules de formation
6. `EvenementSeeder` — Crée des événements de démonstration
7. `ParametreSeeder` — Initialise les paramètres système
8. `NotificationSeeder` — Crée des notifications de démonstration
9. `DemandeProgressionSeeder` — Crée des demandes de progression

---

## Packages supplémentaires à installer

```bash
composer require laravel/sanctum           # API tokens (si besoin)
composer require spatie/laravel-permission # Gestion des rôles et permissions
composer require barryvdh/laravel-dompdf   # Export PDF pour cultes
composer require spatie/laravel-translatable # Traductions (si multi-langue DB)
npm install tailwindcss                    # Déjà dans le template
npm install @fortawesome/fontawesome-free  # Icônes
```

---

## Architecture des vues (Blade)

```
resources/views/
├── layouts/
│   ├── admin.blade.php
│   ├── chef.blade.php
│   └── frontend.blade.php
├── admin/
│   ├── login.blade.php
│   ├── dashboard.blade.php
│   ├── membres/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   ├── chefs/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   ├── groupes/
│   │   └── index.blade.php
│   ├── formations/
│   │   ├── index.blade.php
│   │   └── create.blade.php
│   ├── evenements/
│   │   ├── index.blade.php
│   │   └── create.blade.php
│   ├── cultes/
│   │   ├── index.blade.php
│   │   └── presence.blade.php
│   ├── reunions/
│   │   ├── index.blade.php
│   │   └── create.blade.php
│   ├── communications/
│   │   └── index.blade.php
│   ├── suggestions/
│   │   └── index.blade.php
│   ├── demandes-progression/
│   │   └── index.blade.php
│   ├── parametres/
│   │   └── index.blade.php
│   └── notifications/
│       └── index.blade.php
├── chef/
│   ├── login.blade.php
│   ├── dashboard.blade.php
│   ├── membres/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   ├── reunions/
│   │   ├── index.blade.php
│   │   └── create.blade.php
│   └── demandes-progression/
│       └── index.blade.php
└── frontend/
    ├── login.blade.php
    ├── register.blade.php
    ├── dashboard.blade.php
    ├── formations/
    │   ├── index.blade.php
    │   └── show.blade.php
    ├── evenements/
    │   ├── index.blade.php
    │   └── show.blade.php
    ├── progression/
    │   └── index.blade.php
    ├── notifications/
    │   └── index.blade.php
    ├── compte/
    │   └── edit.blade.php
    └── suggestions/
        └── create.blade.php
```

---

## États de progression (parcours)

```
1. Nouveau Membre  ──>  Star
2. Star            ──>  Pilote
3. Pilote          ──>  Pilier
4. Pilier          ──>  Missionnaire
```

---

## Étapes de réalisation (ordre recommandé)

| # | Étape | Description |
|---|-------|-------------|
| 1 | **Migrations** | Créer toutes les migrations dans l'ordre ci-dessus |
| 2 | **Models** | Créer tous les modèles Eloquent avec relations |
| 3 | **Authentication** | Système d'auth multi-rôle (admin/chef/membre) |
| 4 | **Middleware** | CheckRole, ChefAccess, MaintenanceMode |
| 5 | **Seeders** | Données de test pour chaque table |
| 6 | **Admin Controllers + Vues** | CRUD complet pour chaque section admin |
| 7 | **Chef Controllers + Vues** | Accès restreint aux données du groupe |
| 8 | **Frontend Controllers + Vues** | Espace membre complet |
| 9 | **Routes** | Implémenter toutes les routes |
| 10 | **Notifications** | Système de notifications en base de données |
| 11 | **Rapports / Exports** | Export PDF cultes, statistiques |
| 12 | **Finitions** | Tests, optimisation, déploiement |

---

## Prochaine étape

Quand vous aurez vérifié et validé cette analyse, nous commencerons **étape par étape** :

1. **Étape 1 :** Modification de la migration `users` + création des migrations des tables dépendantes (chefs, groupes, groupe_membre)
2. **Étape 2 :** Models avec relations Eloquent
3. **Étape 3 :** Authentification multi-rôle
4. etc.
