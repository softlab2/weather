<?php

namespace Softlab\Weather;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

use Softlab\Weather\Response as WeatherResponse;
use Softlab\Weather\Point;

use Closure;
use Log;
use Config;
use Cache;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

abstract class Source implements SourceInterface
{
    private $cache_key = 'weather_data';
    private $use_cache = true;

    public function asyncRequest( Point $point, Closure $callback = null ) {
        $httpRequest = $this->prepareRequest( $point );

        $httpClient = new GuzzleClient;
        $promise = $httpClient->requestAsync( 
            $httpRequest->getMethod(), 
            $httpRequest->getUrl(),
            [
                'query' => $httpRequest->getParams(), 
                'headers' => $httpRequest->getHeaders(), 
                'body' => $httpRequest->getBody(), 
            ] 
        );
        $promise->then(
            function (ResponseInterface $res) use($point, $callback) {
                if($res->getStatusCode() == '200'){
                    $key = $this->_getCacheKeyForPoint($point);
                    Cache::put($key, $this->prepareResponse( $res->getBody() ), config('weather.cache',60)*60);
                }
                if( $callback ) {
                    $callback( $res->getBody() );
                }
            },
            function (RequestException $e) {
                throw new AccessDeniedHttpException("123");
                echo $e->getMessage() . "\n";
                echo $e->getRequest()->getMethod();
            }
        );
        $promise->wait();
    }

    public function request( Point $point ) {
    }

    public function cache( $use_cache = true ){
        $this->use_cache = $use_cache;
        return $this;
    }

    public function getAsync( Point $point = mull, Closure $callback = null){
        if(!$point){
            if(!empty(config('weather.default_point'))){
                $point = new Point( config('weather.default_point') );
            } else {
                throw new InvalidArgumentException("Параметр поинт для запроса не определен");
            }
        }

        if( ( $weatherData = $this->retriveDataFromCache() ) instanceof WeatherResponse ) {
            return 
        }
        if( $this->use_cache ){
            $cachedWeathers = $this->Cache::get('weather_data', []);
            if(!empty($cachedWeathers[implode('_', $point->toArray())])){
                return $cachedWeathers[implode('_', $point->toArray())];
            }
        if( !Cache::has($key) || !$this->use_cache ){
            Cache::put($key, 'loading', config('weather.cache',60)*60);
            if($this->async){
                $this->asyncRequest( $point, $callback );
                return new WeatherResponse();
            }else{
                return $this->request( $point );
            }
        }
    }

    public function get( Point $point = null, Closure $callback = null) : WeatherResponse {
        if(!$point){
            if(!empty(config('weather.default_point'))){
                $point = new Point( config('weather.default_point') );
            } else {
                throw new InvalidArgumentException("Параметр поинт для запроса не определен");
            }
        }

        $key = $this->_getCacheKeyForPoint($point);
        $neededLoading = false;
        if( !Cache::has($key) || !$this->use_cache ){
            Cache::put($key, 'loading', config('weather.cache',60)*60);
            if($this->async){
                $this->asyncRequest( $point, $callback );
                return new WeatherResponse();
            }else{
                return $this->request( $point );
            }
        }
    }

    protected function config($param, $default = ''){
        return Config::get("weather.sources.{$this->source}.{$param}", $default);
    }

    private function _getCacheKeyForPoint( Point $point ){
        return $this->cache_key.'_'.implode('_', $point->toArray());
    }
}
