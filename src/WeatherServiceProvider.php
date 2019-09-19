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
            'config'); 

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/weather'),
        ], 'views');
        
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/weather'),
        ], 'public');

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
        
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'weather');
        
        $this->app->bind('weather', function () {
            return Weather::getInstance();
        });
    }

    public function provides(){
        return ['weather'];
    }
}
