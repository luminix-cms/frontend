<?php

/**
 * 
 * This is the configuration file for the `luminix/frontend` package.
 * In this file you can control how the frontend is bootstrapped and what data
 * is available to the frontend.
 * 
 */
return [

    /**
     * Configuration for frontend bootstrapping.
     */
    'boot' => [

        /**
         *
         * The 'method' configuration controls how the boot data is delivered
         * to the frontend. 
         *
         * Available options:
         *  - 'api' (default): will deliver the boot data via an API endpoint. 
         *  - 'embed': will deliver the boot data via an embedded element in the view,
         * through the `@luminixEmbed()` directive.
         *
         */
        'method' => 'api',

        /**
         *
         * The 'includes_manifest' configuration controls whether or not the frontend
         * will include the manifest data in the response. If set to false, the manifest
         * JSON must be bundled into the frontend application, and should be generated
         * via the `php artisan luminix:manifest` command.
         *
         */
        'includes_manifest' => true,

        /**
         *
         * The 'middleware' configuration is used when the boot method is 'api'. It
         * contains the list of middleware that will be applied to the boot route.
         *
         */
        'middleware' => ['api'],

    ],

    /**
     * Configuration for the frontend models.
     */
    'models' => [

        /**
         *
         * The 'public' configuration is used to define which models will be exposed
         * to unauthenticated users.
         *
         */
        'public' => [
            'user',
        ],

        /**
         *
         * The 'exclude' configuration is used to define which models will not be
         * exposed to the frontend at all.
         *
         */
        'exclude' => [],
    ],

    /**
     * Configuration for the frontend routes.
     */
    'routes' => [

        /**
         *
         * The 'public' configuration is used to define which routes will be exposed
         * to unauthenticated users.
         *
         */
        'public' => [
            'login',
            'logout',
            'password.request',
            'password.reset',
            'password.email',
            'password.confirm',
        ],

        /**
         *
         * The 'exclude' configuration is used to define which routes will not be
         * exposed to the frontend at all.
         *
         */
        'exclude' => [],
    ],
    
];