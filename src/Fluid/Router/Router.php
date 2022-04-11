<?php

declare(strict_types=1);

namespace Fluid\Router;

use Fluid\Router\RouterInterface;
use Fluid\Router\Exception\RouterException;
use Fluid\Router\Exception\RouterBadMethodCallException;

class Router implements RouterInterface
{
    /**
     * Returns an array of routes from our routing table
     *
     * @var array
     */
    protected array $routes = [];

    /**
     * Returns an array of the route's parameters
     *
     * @var array
     */
    protected array $params = [];

    /**
     * Adds a suffix onto the controller's name
     *
     * @var string
     */
    protected string $controllerSuffix = 'controller';

    /**
     * @inheritDoc
     */
    public function add(string $route, array $params = []) : void
    {
        $this->routes[$route] = $params;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(string $url): void
    {
        if ($this->matchRoute($url)) {
            $controllerString = $this->params['controller'];
            $controllerString = $this->transformUpperCamelCase($controllerString);
            $controllerString = $this->getNamespace($controllerString);

            if (class_exists($controllerString)) {
                $controllerObject = new $controllerString();
                $action = $this->params['action'];
                $action = $this->transformCamelCase($action);

                if (\is_callable([$controllerObject, $action])) {
                    $controllerObject->$action();
                } else {
                    throw new RouterBadMethodCallException();
                }
            } else {
                throw new RouterException();
            }
        } else {
            throw new RouterException();
        }
    }

    /**
     * Matches the coming route to the routes available in the routing table,
     * setting the $this->params property if a route is found
     *
     * @param string $url
     *
     * @return bool
     */
    private function matchRoute(string $url) : bool
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $param) {
                    if (is_string($key)) {
                        $params[$key] = $param;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
     * Transforms the passed string to Camel Case, first letter included.
     * Example: MyCustomString
     *
     * @param string $string
     *
     * @return string
     */
    public function transformUpperCamelCase(string $string) : string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Transforms the passed string to Camel Case.
     * Example: myCustomString
     *
     * @param string $string
     *
     * @return string
     */
    public function transformCamelCase(string $string) : string
    {
        return \lcfirst($this->transformUpperCamelCase($string));
    }

    /**
     * Get the Namespace for the controller.
     * The namespace is defined within the route parameters only if added.
     *
     * @param string $string
     *
     * @return string
     */
    public function getNameSpace(string $string): string
    {
        $namespace = 'App\Controller\\';
        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace'] . '\\';
        }

        return $namespace;
    }
}
