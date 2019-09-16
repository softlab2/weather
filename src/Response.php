<?php

namespace Softlab\Weather;

use NumberFormatter;

class Response {
    private $temp;

    public function __construct( float $temp ) {
        $this->temp = $temp;
    }

    public function getTemp() : float {
        return $this->temp;
    }

    public function toString(){
        return $this->temp;
    }
}