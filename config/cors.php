<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'storage/*'],  // Add 'storage/*' here
    'allowed_methods' => ['*'],  // Allow all methods
    'allowed_origins' => ['*'],  // Allow all origins (can restrict this if necessary)
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],  // Allow all headers
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];


