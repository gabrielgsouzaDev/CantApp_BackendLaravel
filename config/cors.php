<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Aqui você define quais origens podem acessar sua API, métodos e headers
    | permitidos, além de outras opções importantes para front e mobile.
    |
    */

    'paths' => ['api/*'],

    'allowed_methods' => ['*'], // ou ['GET','POST','PUT','DELETE'] para mais segurança
    'allowed_origins' => [
        'https://cantapp-client.vercel.app',
        'https://cantapp-admin.vercel.app',
        'http://localhost:3000',       // front dev local
        'http://127.0.0.1:3000'        // front dev local alternativo
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // necessário se usar cookies ou autenticação
];
