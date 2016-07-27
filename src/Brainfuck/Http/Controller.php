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
        return $this->view->render($response, 'brainfuck_home.html', [
            //'var' => 'value',
        ]);
    }

    public function encode()
    {
        $input = $this->getRequest()->getBody()->getContents();
        dd($input);
        return $this->getResponse();
    }

    public function decode()
    {

    }
}