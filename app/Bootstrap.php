<?php

namespace Framework;

use League\Container\Container;
use League\Route\RouteCollection;
use League\Route\Strategy\StrategyInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

class Bootstrap
{
    public function load()
    {
        $routes = require_once __DIR__ . '/../config/routes.php';

        $container = $this->loadContainer();
        $route = $this->loadRoutes($container, $routes);
        $response = $route->dispatch($container->get('request'), $container->get('response'));
        $container->get('emitter')->emit($response);
    }

    private function loadContainer()
    {
        $container = new Container;
        $classes = require_once __DIR__ . '/../config/container.php';

        $container->share('response', Response::class);
        $container->share('request', function () {
            return ServerRequestFactory::fromGlobals(
                $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
            );
        });

        $container->share('emitter', SapiEmitter::class);

        foreach ($classes as $alias => $class) {
            $container->add($alias, $class);
        }

        return $container;
    }

    private function loadRoutes($container, array $routes)
    {
        $routeCollection = new RouteCollection($container);

        foreach ($routes as $route) {
            $route = $routeCollection->map($route['method'], $route['path'], $route['handler']);

            if (isset($route['strategy']) && $route['strategy'] instanceof StrategyInterface) {
                $route->setStrategy($route['strategy']);
            }
        }

        return $routeCollection;
    }
}
