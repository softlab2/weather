<?php

namespace Softlab\Weather\Sources;

use App\Widgets\Weather;
use \Softlab\Weather\Source;
use \Softlab\Weather\SourceInterface;
use \Softlab\Weather\Request as WeatherRequest;
use \Softlab\Weather\Response as WeatherResponse;
use \Softlab\Weather\Point;

class YandexSource extends Source implements SourceInterface
{
    // Название источника данных в файле конфигурации
    protected $source = 'yandex';

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
    public function prepareResponse( $response ) : WeatherResponse {
        $data = [];
        if($response->getStatusCode() == '200'){
            $status = WeatherResponse::STATUS_OK;
            $responseObject = json_decode($response->getBody()->getContents());
            if(!empty($responseObject->fact->temp)){
                $data['temp'] = $responseObject->fact->temp;
            }
        }else{
            $status = WeatherResponse::STATUS_ERROR;
        }
        return new WeatherResponse( $status, $data );
    }
}
