<?php

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 03/11/2017
 * Time: 10:57
 */
class Router {

    private $servlets;

    private $response;

    public function __construct(Servlet ...$servlets) {
        foreach ($servlets as $servlet)
            $servlet->setRouter($this);
        $this->servlets = $servlets;
        $this->response = new ServletResponse($this);
    }

    public function dispatch(ServletRequest $request) : void {
        foreach ($this->servlets as $servlet)
            if($servlet->getPath() == $request->getUri())
                switch (strtoupper($request->getMethod())) {
                    case 'GET':
                        $servlet->doGet($request, $this->response);
                        return;
                    case 'POST':
                        $servlet->doPost($request, $this->response);
                        return;
                    default:
                        $this->response->setStatus(405);
                        return;
                }
        $this->response->setStatus(404);
    }

    public function output() : void {
        echo $this->response;
    }
}