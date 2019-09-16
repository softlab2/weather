<?php

namespace Softlab\Weather;

use Illuminate\Support\Facades\Facade;

class WeatherFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'weather';
    }
}
