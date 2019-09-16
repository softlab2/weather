<?php

namespace Softlab\Weather;

use \Softlab\Weather\Request as WeatherRequest;
use \Softlab\Weather\Response as WeatherResponse;

interface SourceInterface
{
    /**
     * Receive data from wheater api service.
     *
     * @param  \Softlab\Weather\Request  $request
     * @return \Softlab\Weather\Response
     */
    public static function weather(WeatherRequest $request) : WeatherResponse;

    /**
     * Receive data from wheater api service.
     *
     * @param  \Softlab\Weather\Point  $point
     * @return \Softlab\Weather\Request
     */
    public function prepareRequest( Point $point ) : WeatherRequest;

    /**
     * Receive data from wheater api service.
     *
     * @return \Softlab\Weather\Response
     */
    public function prepareResponse( array $data ) : WeatherResponse;
}