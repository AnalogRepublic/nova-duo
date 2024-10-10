[![Latest Version on Packagist](https://img.shields.io/packagist/v/analogrepublic/nova-duo.svg?style=flat-square)](https://packagist.org/packages/analogrepublic/nova-duo)
[![Total Downloads](https://img.shields.io/packagist/dt/analogrepublic/nova-duo.svg?style=flat-square)](https://packagist.org/packages/analogrepublic/nova-duo)

# Nova Duo
Laravel Nova Duo multi factor authentication.

Install the package

`` composer require analogrepublic/nova-duo ``


1. Publish config

`` php artisan vendor:publish --provider="AnalogRepublic\NovaDuo\ToolServiceProvider" ``


Change configs as your needs

```@php

return [

    'enabled' => env('NOVA_DUO_ENABLED', true),

    'duo' => [
        'client_id' => env('NOVA_DUO_CLIENT_ID'),
        'client_secret' => env('NOVA_DUO_CLIENT_SECRET'),
        'api_hostname' => env('NOVA_DUO_API_HOSTNAME'),
    ]

];


```


2. Add Nova Duo middleware to Nova config file


``` 
/*
    |--------------------------------------------------------------------------
    | Nova Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be assigned to every Nova route, giving you the
    | chance to add your own middleware to this stack or override any of
    | the existing middleware. Or, you can just stick with this stack.
    |
    */

    'middleware' => [
        ...
        \AnalogRepublic\NovaDuo\Http\Middleware\TwoFa::class
    ],

```


3. Register Nova Duo tool in Nova Service Provider

``` 
<?php

class NovaServiceProvider extends NovaApplicationServiceProvider{

public function tools()
    {
        return [
            ...
            new \AnalogRepublic\NovaDuo\NovaDuo()

        ];
    }

}


```

4. You are done !
