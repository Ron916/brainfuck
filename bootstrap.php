<?php

require __DIR__ . '/helper_functions.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$app = new \Slim\App($config);

$container = $app->getContainer();

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(projectroot() . '/src/Brainfuck/Html', [
        'cache' => false,
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));
    return $view;
};

$routes = new \Ron916\Brainfuck\Http\Routes($app);
$routes->set();

$app->run();