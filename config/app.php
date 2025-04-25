<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | Nom de votre application. Cela sera utilisé dans les notifications et
    | dans les éléments d'interface utilisateur où ce nom doit être affiché.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | Définit l'environnement dans lequel votre application s'exécute. Cela peut
    | influencer la configuration des services utilisés par l'application.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | Mode de débogage de l'application. En mode "debug", des messages d'erreur
    | détaillés avec des traces d'exécution seront affichés lors des erreurs.
    | Désactivez cela en production pour afficher une page d'erreur générique.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | Cette URL est utilisée par la console pour générer correctement les URLs
    | lorsque vous utilisez l'outil en ligne de commande Artisan.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Fuseau horaire par défaut de votre application. Cela sera utilisé par les
    | fonctions PHP liées aux dates et heures. Par défaut : "UTC".
    |
    */

    'timezone' => env('APP_TIMEZONE', 'Europe/Paris'),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | La locale de l'application détermine la langue par défaut utilisée
    | par les méthodes de traduction et de localisation de Laravel.
    |
    */

    'locale' => env('APP_LOCALE', 'fr'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'fr_FR'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | Cette clé est utilisée par les services de chiffrement de Laravel et doit
    | être définie à une chaîne aléatoire de 32 caractères pour garantir que
    | toutes les valeurs chiffrées soient sécurisées.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => array_filter(
        explode(',', env('APP_PREVIOUS_KEYS', ''))
    ),

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | Ces options de configuration déterminent le pilote utilisé pour gérer le
    | statut du mode de maintenance de Laravel. Le pilote "cache" permet de
    | contrôler le mode maintenance sur plusieurs machines.
    |
    | Pilotes supportés : "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
