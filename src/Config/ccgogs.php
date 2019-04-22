<?php

return [
    
    /**
     * -----------------------------------------
     *  API Configuration
     * -----------------------------------------
     * This configure sevaral API related
     * Configurations.
     */
    'api'   =>  [

        /**
         * ENDPOINT
         * --------------------------------------
         * This Configures the Endpoints of your
         * hosted gogs server. This is basically
         * the Base URL to your Gogs Server API
         * 
         * EXAMPLE: https://git.example.io/api/v1
         */

        'endpoint'  => env('CLIPPEDCODE_GOGS_ENDPOINT', ''),

        /**
         * ACCESS TOKEN
         * --------------------------------------
         * The token for an authorized user to
         * query Gogs API.
         */

        'token'  => env('CLIPPEDCODE_GOGS_TOKEN'),
    ],
    
];