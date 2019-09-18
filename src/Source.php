<?php
declare(strict_types = 1);

namespace Softlab\Weather;

use GuzzleHttp\Psr7\Request as HttpRequest;
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
    private $use_cache = true;
    private $async = false;

    public function __construct(){
        $this->use_cache = config('weather.cache', false);
    }

    private function request( Point $point, Closure $callback = null ) : WeatherResponse {
        $weatherResponse = $this->retriveDataFromCache( $point );
        if( $weatherResponse() ) {
            return $weatherResponse;
        }else{
            $weatherRequest = $this->prepareRequest( $point );

            $client = new GuzzleClient;

            $httpRequest = new HttpRequest(
                $weatherRequest->getMethod(), 
                $weatherRequest->getUrl(),
                $weatherRequest->getHeaders(), 
                $weatherRequest->getBody()
            );

            if( $this->async ){
                $promise = $client->sendAsync($httpRequest, $weatherRequest->getParams());
                $promise->then(
                    function (ResponseInterface $res) use($point, $callback) {
                        if($res->getStatusCode() == '200'){
                            $weatherResponse = $this->prepareResponse( $res );
                            $this->storeDataToCache( $point, $weatherResponse );
                            if( $callback ) {
                                $callback( $weatherResponse );
                            }
                        }
                    },
                    function (RequestException $e) {
                        throw new AccessDeniedHttpException("123");
                        echo $e->getMessage() . "\n";
                        echo $e->getRequest()->getMethod();
                    }
                );
                $promise->wait();
                $weatherResponse = new WeatherResponse;
            }else{
                $response = $client->send( $httpRequest, $weatherRequest->getParams() );
                $weatherResponse = $this->prepareResponse( $response );
                $this->storeDataToCache( $point, $weatherResponse );
            }
            return $weatherResponse;
        }
    }

    public function cache( $use_cache = true ) : Source{
        $this->use_cache = $use_cache;
        return $this;
    }

    public function async( Point $point, $callback = null ) {
        $this->async = true;
        $this->get( $point, $callback );
    }

    public function get( Point $point ) : WeatherResponse{
        return $this->request( $point );
    }

    protected function config(string $param, string $default = '') {
        return Config::get("weather.sources.{$this->source}.{$param}", $default);
    }

    private function retriveDataFromCache( Point $point ) : WeatherResponse {
        if( $this->use_cache ){
            return Cache::store(config('weather.cache_store'))->get( $point->toString(), new WeatherResponse);                
        }else{
            return new WeatherResponse;
        }
    }

    private function storeDataToCache( Point $point, WeatherResponse $weatherResponse ) {
        if( $this->use_cache ){
            Cache::store(config('weather.cache_store'))->put( $point->toString() ,config('weather.cache_time', 60)*60, $weatherResponse);
        }
    }

}
