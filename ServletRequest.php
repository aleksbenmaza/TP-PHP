<?php

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 03/11/2017
 * Time: 11:03
 */
class ServletRequest {

    private $method;

    private $headers;

    private $uri;

    private $request_params;

    private $data;

    private $session;

    /**
     * ServletRequest constructor.
     * @param $headers
     * @param $request_params
     * @param $request_body
     */
    public function __construct(string $method, array $headers, string $uri, stdClass $request_params, stdClass $request_data, array & $session) {
        $this->method = $method;
        $this->headers = $headers;
        $this->uri = $uri;
        $this->request_params = $request_params;
        $this->request_data = $request_data;
        $this->session = $session;
    }

    /**
     * @return string
     */
    public function getMethod(): string {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getHeaders() : array {
        return $this->headers;
    }

    /**
     * @return mixed
     */
    public function getRequestParams() : stdClass {
        return $this->request_params;
    }

    /**
     * @return mixed
     */
    public function getRequestData() : stdClass {
        return $this->request_data;
    }

    /**
     * @return array
     */
    public function & getSession(): array {
        return $this->session;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }



    /**
     * @param string $uri
     */
    public function setUri(string $uri) {
        $this->uri = $uri;
    }
}