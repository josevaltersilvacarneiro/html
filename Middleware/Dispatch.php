<?php

declare(strict_types=1);

/**
 * This package is responsible for initializing the service.
 * PHP VERSION >= 8.2.0
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
 * @category Middleware
 * @package  Josevaltersilvacarneiro\Html\Middleware
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  https://www.gnu.org/licenses/quick-guide-gplv3.html GPLv3
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Middleware
 */

namespace Josevaltersilvacarneiro\Html\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 * This class instantiates the controller object and instructs it to
 * process the request.
 * 
 * @category  Dispatch
 * @package   Josevaltersilvacarneiro\Html\Middleware
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.13.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Middleware
 * @see       https://www.php-fig.org/psr/psr-15/
 */
final class Dispatch implements MiddlewareInterface
{
    /**
     * This method processes the request and returns the response.
     * 
     * @param ServerRequestInterface  $request Request
     * @param RequestHandlerInterface $handler Controller
     * 
     * @return ResponseInterface Response
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {

        $response = $handler->handle($request);

        return $response;
    }
}
