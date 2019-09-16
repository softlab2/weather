<?php

namespace Softlab\Weather\Sources;

use \Softlab\Weather\Source;
use \Softlab\Weather\SourceInterface;
use \Softlab\Weather\Request as WeatherRequest;
use \Softlab\Weather\Response as WeatherResponse;
use \Softlab\Weather\Point;

class YandexSource extends Source implements SourceInterface
{
    protected $source = 'yandex';

   /**
     * Receive data from wheater api service.
     *
     * @param  \Softlab\Weather\Data\Request  $request
     * @return \Softlab\Weather\Data\Response
     */
    public static function weather(WeatherRequest $request) : WeatherResponse
    {
        //static::request()
        return new WeatherResponse([]);
    }

    /**
     * Receive data from wheater api service.
     *
     * @return \Softlab\Weather\Request
     */
    public function prepareRequest( Point $point ) : WeatherRequest {
        $params = [
            'lat' => $point->lat(),
            'lon' => $point->lng()
        ];
        
        $headers = [
            'X-Yandex-API-Key' => $this->config('api_key')
        ];

        return new WeatherRequest(
            $this->config('url'),
            $params,
            $this->config('method'),
            $headers
        );
    }

    /**
     * Receive data from wheater api service.
     *
     * @return \Softlab\Weather\Request
     */
    public function prepareResponse( array $data ) : WeatherResponse {
        return new WeatherResponse( (float) $data['temp']);
    }

    public function __construct()
    {
        $params = [
            // 'lat' => $point->lat,
            // 'lon' => $point->lng,
            'lang' => $this->config('lang'),
            'limit' => $this->config('limit'),
            'hours' => $this->config('hours'),
            'extra' => $this->config('extra'),
        ];

        $headers = [
            'X-Yandex-API-Key' => $this->config('api_key')
        ];

        parent::__construct(
            $this->config('url'),
            $params,
            $this->config('method'),
            $headers
        );
    }
}
