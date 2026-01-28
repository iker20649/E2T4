<?php
use Illuminate\Support\Str;
return [
    'driver' => 'cookie',
    'lifetime' => 120,
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => storage_path('framework/sessions'),
    'connection' => null,
    'table' => 'sessions',
    'store' => null,
    'lottery' => [2, 100],
    'cookie' => 'ileapp_session',
    'path' => '/',
    'domain' => '127.0.0.1',
    'secure' => false,
    'http_only' => true,
    'same_site' => 'lax',
    'partitioned' => false,
];