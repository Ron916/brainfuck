<?php

namespace Ron916\Brainfuck\Http;

use Slim\App;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


/**
 * Class Routing
 *
 * @package Ron916\Brainfuck\Http
 */
class Routes
{
    /**
     * @var App
     */
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function set()
    {
        $app = $this->getApp();

        $this->app->get('/brainfuck', function(Request $request, Response $response) use($app) {
            $controller = new Controller($app, $request, $response);
            return $controller->start();
        });

        $this->app->post('/brainfuck/encode', function(Request $request, Response $response) use($app) {
            $controller = new Controller($app, $request, $response);
            return $controller->encode();
        })->setName('encode');

        $this->app->post('/brainfuck/decode', function(Request $request, Response $response) use($app) {
            $controller = new Controller($app, $request, $response);
            return $controller->decode();
        })->setName('decode');
    }

    /**
     * @return App
     */
    private function getApp(): App
    {
        return $this->app;
    }
}