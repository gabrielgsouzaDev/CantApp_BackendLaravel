<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Configuração para permitir que seu frontend faça requisições para a API
    | sem bloqueio do navegador (CORS), usando domínios específicos e suportando cookies.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    // Permite todos os métodos HTTP
    'allowed_methods' => ['*'],

    // Origens permitidas: front local e produção
    'allowed_origins' => [
        'https://cantapp-client.vercel.app',
        'https://cantapp-admin.vercel.app',
        'http://localhost:3000',
        'http://127.0.0.1:3000',
    ],

    'allowed_origins_patterns' => [],

    // Permite todos os headers do frontend
    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    // Cache de pré-flight request (em segundos)
    'max_age' => 0,

    // Necessário para cookies/sessões/autenticação via front
    'supports_credentials' => true,
];
