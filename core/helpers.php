<?php

use Core\Debug\Debugger;
use Core\Router\Router;

if (!function_exists('dd')) {
    function dd(): void
    {
        Debugger::dd(...func_get_args());
    }
}

# verificar se existe a rota antes de definir
if (!function_exists('route')) {
    /**
     * @param string $name
     * @param mixed[] $params
     * @return string
     */
    function route(string $name, $params = []): string
    { # vai encapsular a chamada para getRoutePathByName 
        return Router::getInstance()->getRoutePathByName($name, $params);
    }
}
