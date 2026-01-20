<?php

return [
    // Permitimos todas las rutas de la API
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // ¡OJO AQUÍ! Cambiamos el 3000 por el 5173 que es el de vuestro Vue
    'allowed_origins' => ['http://localhost:5173'], 

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];