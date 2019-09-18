<?php

namespace Softlab\Weather;

use InvalidArgumentException;

class Point {
    private $lat;    
    private $lng;

    public function __construct( array $data )
    {
        if(empty($data['lat']) || empty($data['lng'])) {
            throw new InvalidArgumentException("Данные для точки не содержат необходимых данных.");
        }

        $this->lat = $data['lat'];
        $this->lng = $data['lng'];
    }

    public function lat() : float{
        return $this->lat;
    }

    public function lng() : float{
        return $this->lng;
    }

    public function toArray() {
        return ['lat'=>$this->lat, 'lng'=>$this->lng];
    }

    public function toString() {
        return implode('_', $this->toArray());
    }
}