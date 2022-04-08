<?php

declare(strict_types=1);

namespace Fluid\Router;

interface RouterInterface
{
    /**
     * Create a new route to the routing table
     *
     * @param string $route
     * @param array $params
     *
     * @return void
     */
    public function add(string $route, array $params) : void;

    /**
     * Dispatch the route creating controller's objects executing the default
     * method on that controller's object
     *
     * @param string $url
     *
     * @return void
     */
    public function dispatch(string $url) : void;
}
