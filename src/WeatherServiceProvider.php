<?php

namespace Softlab\Weather;

use Illuminate\Support\ServiceProvider;

class WeatherServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__.'/../config/weather.php';

        $this->publishes([$configPath => config_path('weather.php')],
            'weather'); 

        $this->app['weather']->add('yandex', \Softlab\Weather\Sources\YandexSource::class);

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        require __DIR__ . '/helpers.php';
        
        $this->app->bind('weather', function () {
            return Weather::getInstance();
        });
    }

    public function provides(){
        return ['weather'];
    }
}
