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
         * hosted Git server. This is basically
         * the Base URL to your Git Server API
         * 
         * EXAMPLE: https://git.example.io/api/v1
         */

        'endpoint'  => env('CLIPPEDCODE_GIT_ENDPOINT', ''),

        /**
         * ACCESS TOKEN
         * --------------------------------------
         * The token for an authorized user to
         * query Git API.
         */

        'token'  => env('CLIPPEDCODE_GIT_TOKEN'),
    ],
    
];