<?php

namespace Softlab\Weather;

use Config;

class Request {
    private $method;
    private $url;
    private $headers;
    private $body;
    private $params;
    private $source;

    public function __construct( $url, $params = [], $method = 'GET', $headers = [], $body = '') {
        $this->url = $url;
        $this->method = $method;
        $this->params = $params;
        $this->headers = $headers;
        $this->body = $body;
    }

    public function getUrl() : string {
        return $this->url;
    }

    public function getMethod() : string {
        return $this->method;
    }

    public function getParams() : array {
        return $this->params;
    }

    public function getHeaders() : array {
        return $this->headers;
    }

    public function getBody(): string {
        return $this->body;
    }

}