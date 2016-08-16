<?php

namespace Ron916\Brainfuck\Http;

use Slim\App;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class BaseControllerAbstract
 *
 * @package Ron916\Brainfuck\Http
 */
abstract class BaseControllerAbstract
{
    /**
     * @var App
     */
    private $app;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var ResponseInterface
     */
    private $response;

    public function __construct(App $app, RequestInterface $request, ResponseInterface $response)
    {
        $this->app = $app;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return App
     */
    protected function getApp(): App
    {
        return $this->app;
    }

    /**
     * @return RequestInterface
     */
    protected function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @return ResponseInterface
     */
    protected function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}