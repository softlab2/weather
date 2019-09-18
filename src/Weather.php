<?php

namespace Softlab\Weather;

use Closure;
use Softlab\Weather\Response as WeatherResponse;
use \Softlab\Weather\Source as WeatherSource;
use Softlab\Weather\Point;
use InvalidArgumentException;
use Config;

class Weather
{
    private static $_instance;
    
    private $sources = [];
    private $default_source_alias = '';
    
    private function __construct(){
        $this->default_source_alias = Config::get('weather.default');
    }
    protected function __clone() {}

    public static function getInstance(): Weather {
        if(is_null(self::$_instance))
        {
            self::$_instance = new Weather();
        }
        return self::$_instance;
    }

    public function add(string $alias, string $source) : Weather {
        $sourceObject = new $source;
        
        if(!$sourceObject instanceof WeatherSource){
            throw new InvalidArgumentException("Класс {$source} не является источником данных WeatherSource");
        }

        $this->sources[$alias] = $sourceObject;

        return $this;
    }

    public function __get( string $alias) : WeatherSource {
        if(isset($this->sources[$alias])){
            return $this->sources[$alias];            
        } else {
            throw new InvalidArgumentException("Источник {$alias} не определен");
        }
    }

    public function source( string $alias ) {
        $this->default_source_alias = $alias;
        return $this;
    }

    public function request() : Source {
        $this->{$this->default_source_alias};
    }

    public function get( array $coords ) : WeatherResponse {
        return $this->{$this->default_source_alias}->get( new Point( $coords ) );
    }
}
