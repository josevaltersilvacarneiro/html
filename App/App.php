<?php

declare(strict_types=1);

/**
 * This package is responsible for initializing the service.
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
 * @package Josevaltersilvacarneiro\Html\App
 */

namespace Josevaltersilvacarneiro\Html\App;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 * This class instantiates the controller object and instructs it to
 * process the request.
 * 
 * @author    José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version   0.11
 * @see       https://www.php-fig.org/psr/psr-15/
 * @copyright Copyright (C) 2023, José V S Carneiro
 * @license   GPLv3
 */
final class App implements MiddlewareInterface
{
    public function __construct()
    {}

    /**
     * @return ResponseInterface
     * 
     * @author    José V S Carneiro <git@josevaltersilvacarneiro.net>
     * @version   0.1
     * @access    public
     * @see       https://www.php-fig.org/psr/psr-15/
     * @copyright Copyright (C) 2023, José V S Carneiro
     * @license   GPLv3
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        return $response;
    }
}
