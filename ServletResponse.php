<?php

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 03/11/2017
 * Time: 11:08
 */
class ServletResponse {

    private $router;

    private $attributes;

    private $status;

    private $view;

    private $rendering  = FALSE;

    /**
     * ServletResponse constructor.
     * @param $attributes
     * @param $status
     * @param $body
     */
    public function __construct(Router $router) {
        $this->router = $router;
    }

    /**
     * @param string $attributes
     */
    public function setAttributes(array $attributes) : void{
        $this->attributes = $attributes;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status) : void {
        $this->status = $status;
    }

    public function render(string $view) : void {
        $this->rendering = TRUE;
        $this->view = $view;
    }

    public function forward(ServletRequest $servletRequest, string $uri) : void {
        $servletRequest->setUri($uri);
        $this->router->dispatch($servletRequest);
    }

    public function sendRedirect(string $view) : void {
        header('Location: ' . $view, 301);
    }

    /**
     * @return bool
     */
    public function isRendering(): bool {
        return $this->rendering;
    }

    public function __toString() : string {
        ob_start();
        if($this->rendering) {
            if ($this->attributes)
                extract($this->attributes);

            require_once $this->view . '.php';
        }
        http_response_code($this->status);
        return ob_get_clean();
    }

}