<?php

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 03/11/2017
 * Time: 10:59
 */
abstract class Servlet {

    private $router;

    private $path;

    public function __construct(string $path) {
        $this->path   = $path;
    }

    public abstract function doGet(ServletRequest $request, ServletResponse $response) : void;

    public abstract function doPost(ServletRequest $request, ServletResponse $response) : void;

    /**
     * @param Router $router
     */
    public function setRouter(Router $router) : void{
        $this->router = $router;
    }

    /**
     * @return Router
     */
    public function getRouter(): Router {
        return $this->router;
    }

    /**
     * @return string
     */
    public function getPath(): string {
        return $this->path;
    }
}