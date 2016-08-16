<?php

namespace Ron916\Brainfuck\Http;

/**
 * Class Controller
 *
 * @package Ron916\Brainfuck\Http
 */
class Controller extends BaseControllerAbstract
{
    public function start()
    {
        $response = $this->getResponse();
        $view = $this->getApp()->getContainer()['view'];
        $view->render($response, 'brainfuck_home.html', [
            'app' => $this->getApp()
        ]);
    }

    public function encode()
    {
        $input = $this->getRequest()->getBody()->getContents();
        $response = $this->getResponse();
        return $response;
    }

    public function decode()
    {
        $input = $this->getRequest()->getBody()->getContents();
        $response = $this->getResponse();
        return $response;
    }
}