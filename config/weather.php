<?php

/**
 * Weather settings
 */
return [
    /*
    |--------------------------------------------------------------------------
    | Default Cache life time in minutes
    |--------------------------------------------------------------------------
    |
    */    
    'default_point'         => env('WEATER_DEFAULT_POINT', ['lat' => 53.2423778 , 'lng' => 34.3668288]),      //Брянск
    'default_name'          => env('WEATER_DEFAULT_NAME', 'Брянск'),
    /*
    |--------------------------------------------------------------------------
    | Default Cache life time in minutes
    |--------------------------------------------------------------------------
    |
    */    
    'cache_time'         => env('WEATHER_CACHE_TIME', 60),      
    
    /*
    |--------------------------------------------------------------------------
    | Default Cache life time in minutes
    |--------------------------------------------------------------------------
    |
    */    
    'cache'         => env('WEATHER_CACHE', false),      
    
    /*
    |--------------------------------------------------------------------------
    | Default source
    |--------------------------------------------------------------------------
    |
    | Supported: "yandex", "darksky"
    |
    */    
    'default' => env('WEATHER_SOURCE', 'yandex'), //Default source
    
    /*
    |--------------------------------------------------------------------------
    | Default source
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the weather sources
    | 
    */    
    'sources'       => [
        // Docs: https://yandex.ru/dev/weather/doc/dg/concepts/forecast-test-docpage/
        'yandex'    => [
            'url' => env('YANDEX_WEATHER_URL', 'https://api.weather.yandex.ru/v1/forecast'),
            'api_key' => env('YANDEX_WEATHER_API_KEY', '545cde47-8714-48fe-b423-0cab78b29575'), //your api key, required
            'lang'  => env('YANDEX_WEATHER_LANG', 'ru_RU'), // response language 
            'limit'  => env('YANDEX_WEATHER_LIMIT', 1), // number of days in the forecast, including current
            'hours'  => env('YANDEX_WEATHER_HOURS', false), // hourly forecat - true/false 
            'extra'  => env('YANDEX_WEATHER_EXTRA', false), // get extended information - true/false
            'method' => 'GET',
        ],
        'darksky'   => [

        ]
    ]
];
