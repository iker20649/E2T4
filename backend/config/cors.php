<?php

return [
   // config/cors.php

'paths' => ['api/*', 'register', 'login', 'logout', 'sanctum/csrf-cookie'],

'allowed_methods' => ['*'],

'allowed_origins' => ['http://localhost:5173'], // <--- AQUÍ tu URL de Vue

'allowed_origins_patterns' => [],

'allowed_headers' => ['*'],

'exposed_headers' => [],

'max_age' => 0,

'supports_credentials' => true, // <--- IMPORTANTE ponerlo en true
];