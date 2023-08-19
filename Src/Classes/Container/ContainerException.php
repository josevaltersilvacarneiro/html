<?php

declare(strict_types=1);

/**
 * This package is responsible for managing the instantiation of controllers
 * along with their required dependencies within an application. It serves as
 * a central mechanism for maintaining and resolving dependencies, allowing
 * controllers to be created with the required services, repositories, or other
 * components.
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
 * @package Josevaltersilvacarneiro\Html\Src\Classes\Container
 */

namespace Josevaltersilvacarneiro\Html\Src\Classes\Containeir;

use Psr\Container\ContainerExceptionInterface;

class NotFoundException extends \Exception implements ContainerExceptionInterface
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
