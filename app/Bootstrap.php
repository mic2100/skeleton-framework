<?php

namespace Framework;

use League\Container\Container;
use League\Event\Emitter;
use League\Route\RouteCollection;
use League\Route\Strategy\StrategyInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

/**
 * Class Bootstrap
 * @package Framework
 */
class Bootstrap
{
    /**
     * Load the required components, get the request and emit the response
     */
    public function load()
    {
        $container = $this->loadContainer();
        switch ($container->get('config')->get('environment')) {
            case 'production':
                error_reporting(0);
                break;

            case 'staging':
                error_reporting(E_ERROR);
                break;

            case 'development':
            default:
                error_reporting(E_ALL);
                break;
        }
        $routes = require_once __DIR__ . '/../config/routes.php';
        $route = $this->loadRoutes($container, $routes);
        $response = $route->dispatch($container->get('request'), $container->get('response'));
        $container->get('emitter')->emit($response);
    }

    /**
     * Load the DI container and return it
     *
     * @return Container
     */
    private function loadContainer()
    {
        $container = new Container;
        $objects = require_once __DIR__ . '/../config/container.php';

        $container->share('response', Response::class);
        $container->share('request', function () {
            return ServerRequestFactory::fromGlobals(
                $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
            );
        });

        $container->share('emitter', SapiEmitter::class);

        foreach ($objects as $object) {
            $container->add(
                $object['name'],
                $object['concrete'],
                isset($object['shared']) ? $object['shared'] : false
            );
        }

        $container->share('events', $this->loadEvents($container));

        return $container;
    }

    /**
     * Loads the routes and return the RouteCollection
     *
     * @param Container $container
     * @param array $routes
     * @return RouteCollection
     */
    private function loadRoutes(Container $container, array $routes)
    {
        $routeCollection = new RouteCollection($container);

        foreach ($routes as $route) {
            $map = $routeCollection->map($route['method'], $route['path'], $route['handler']);

            if (isset($route['strategy']) && $route['strategy'] instanceof StrategyInterface) {
                $map->setStrategy($route['strategy']);
            }
        }

        return $routeCollection;
    }

    /**
     * Loads the events emitter
     *
     * @param Container $container
     * @return Emitter
     */
    private function loadEvents(Container $container)
    {
        $events = require_once __DIR__ . '/../config/events.php';
        $emitter = new Emitter;

        foreach ($events as $event) {
            $emitter->addListener(
                $event['name'],
                $event['listener'],
                isset($event['priority']) ? $event['priority'] : 0
            );
        }

        return $emitter;
    }
}
