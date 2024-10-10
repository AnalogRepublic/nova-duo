<?php

return [

    'enabled' => env('NOVA_DUO_ENABLED', false),

    'duo' => [
        'client_id' => env('NOVA_DUO_CLIENT_ID'),
        'client_secret' => env('NOVA_DUO_CLIENT_SECRET'),
        'api_hostname' => env('NOVA_DUO_API_HOSTNAME'),
    ]

];
