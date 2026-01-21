<?php

return [
    // Hemos añadido 'storage/*' a la lista de rutas permitidas
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'storage/*'],

    'allowed_methods' => ['*'],

    // Asegúrate de que este es el puerto donde corre tu Vue
    'allowed_origins' => ['http://localhost:5173'], 

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];