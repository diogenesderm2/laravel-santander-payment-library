<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Santander API Configuration
    |--------------------------------------------------------------------------
    |
    | Configurações para integração com a API do Santander
    |
    */

    'url' => env('SANTANDER_URL', 'https://trust-open.api.santander.com.br/auth/oauth/v2/token'),
    
    'client_id' => env('SANTANDER_CLIENT_ID'),
    
    'client_secret' => env('SANTANDER_CLIENT_SECRET'),
    
    'certificates' => [
        'crt' => env('SANTANDER_CERTIFICATE_CRT', 'certificates/santander/certificate.crt'),
        'key' => env('SANTANDER_CERTIFICATE_KEY', 'certificates/santander/private.key'),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | SSL Configuration
    |--------------------------------------------------------------------------
    */
    
    'ssl' => [
        'verify_peer' => env('SANTANDER_SSL_VERIFY_PEER', false),
        'verify_host' => env('SANTANDER_SSL_VERIFY_HOST', false),
    ],
];