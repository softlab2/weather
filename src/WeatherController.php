<?php

namespace Softlab\Weather;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Softlab\Weather\Response as WeatherResponse;
use Weather;

class WeatherController extends Controller
{
    public function get( Request $request ) {
        /**
         * @var WeatherResponse
         */
        $response = null;

        /**
         * @var array
         */
        $coords = [];

        $input = $request->all();
        
        if( empty($input['lat']) || empty($input['lng']) ){
            throw new InvalidArgumentException("Даннве точки заданы неверно.");
        }else{
            $coords = [
                'lat' => $input['lat'],
                'lng' => $input['lng'],
            ];
        }
        $response = Weather::get( $coords );

        return response()->json(['status'=>$response->getStatus(), 'data'=>$response->getData()]);
    }
}