<?php

namespace Ron916\Brainfuck\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;

/**
 * Class APIController
 *
 * @package Ron916\Brainfuck\Http
 */
class APIController extends BaseControllerAbstract
{
    public function __construct(
        App $app,
        RequestInterface $request,
        ResponseInterface $response
    ) {
        parent::__construct($app, $request, $response);
    }

    public function encode()
    {
        // do it and return json :)
    }

    public function decode()
    {
        // do it and return json :)
    }
}