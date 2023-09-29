<?php

declare(strict_types=1);

/**
 * This front controller accepts all HTTP requests and delegates them to the
 * controller according to the route.
 * PHP VERSION 8.2.0
 * 
 * Copyright (C) 2023, José V S Carneiro
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful,    
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License 
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 * 
 * @category FrontController
 * @package  Josevaltersilvacarneiro\Html
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  https://www.gnu.org/licenses/quick-guide-gplv3.html GPLv3
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/
 */

include_once 'Src/vendor/autoload.php';

# DEVELOPMENT

$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__ . '/.env');

# END DEVELOPMENT

require_once 'Settings/Database.php';
require_once 'Settings/Mail.php';

include_once 'Settings/Server.php';
include_once 'Settings/Public.php';

use Josevaltersilvacarneiro\Html\Src\Classes\Container\Container;

use Nyholm\Psr7\Factory\Psr17Factory;

try {
    $routes       = require_once __DIR__ . '/Routes/Routes.php';
    $html         = include_once __DIR__ . '/Bootstrap/App.php';

    $container    = new Container();
    $psr17Factory = new Psr17Factory();

    $request = $psr17Factory->createServerRequest(
        $_SERVER['REQUEST_METHOD'],
        mb_substr(__URL__, 0, -1) . $_SERVER['REQUEST_URI'], $_SERVER
    );

    $queryStringPosition = mb_strpos($_SERVER['REQUEST_URI'], '?');

    $route = $queryStringPosition === false
        ? $routes[$_SERVER['REQUEST_METHOD'] . '|' . $_SERVER['REQUEST_URI']]
        : $routes[$_SERVER['REQUEST_METHOD'] . '|' . mb_substr($_SERVER['REQUEST_URI'], 0, $queryStringPosition)];

    $container->add($route['controller'], $route['dependencies']);

    $controller = $container->get($route['controller']);
    $response   = $html->process($request, $controller);

    http_response_code($response->getStatusCode());

    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            header(sprintf('%s: %s', $name, $value), false);
        }
    }

    echo $response->getBody();
} catch (\Exception $e) {
    echo $e->getMessage();
    foreach ($e->getTrace() as $exc) {
        echo var_dump($exc);
    }
}
