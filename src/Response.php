<?php
declare(strict_types = 1);

namespace Softlab\Weather;

class Response {
    const STATUS_NOT_SET = -1;
    const STATUS_ERROR = 0;
    const STATUS_OK = 1;

    private $data = [];
    private $status;

    public function __construct( int $status = -1, array $data = []) {
        $this->status = $status;
        $this->data = $data;
    }

    public function getData() : array {
        return $this->data;
    }

    public function getStatus() : int {
        return $this->status;
    }

    public function toString(){
        if(!empty($this->data['temp']))
            return $this->data['temp'];
        else
            return '';
    }

    public function __invoke() :bool {
        return (bool)$this->data;
    }
}