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

namespace Josevaltersilvacarneiro\Html\Src\Classes\Container;

use Josevaltersilvacarneiro\Html\Src\Classes\Containeir\NotFoundException;
use Josevaltersilvacarneiro\Html\App\Controller\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Container implements ContainerInterface
{
    /** @var array<string,Controller> Array of namespaces for the controllers */
    private array $container = [];

    /**
     * @param string $className Name of the class to be instantiated @example 'Josevaltersilvacarneiro\Html\App\Controller\Home'
     * @param mixed $parameters Parameters to be passed to the class constructor
     * 
     * @return void
     */
    public function add(string $className, mixed ...$parameters): void
    {
        $this->container[$className] = function ($container) use ($className, $parameters) {
            return new $className(...$parameters);
        };
    }

    /**
     * @param string $id Indentifier of the object to be instantiated
     * 
     * @throws NotFoundExceptionInterface No entry was found for this identifier
     * @throws ContainerExceptionInterface Error while retrieving the entry
     */
    public function get(string $id): mixed
    {
        if (!$this->has($id)) {
            throw new NotFoundException("{$id} not found in container");
        }

        return $this->container[$id]($this);
    }

    /**
     * @return bool if the container can return an entry for the given identifier; false otherwise
     */
    public function has($id): bool
    {
        return isset($this->container[$id]);
    }
}
