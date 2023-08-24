<?php

declare(strict_types=1);

/**
 * This package is responsible for managing the instantiation of controllers
 * along with their required dependencies within an application. It serves as
 * a central mechanism for maintaining and resolving dependencies, allowing
 * controllers to be created with the required services, repositories, or other
 * components.
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
 * @category Container
 * @package  Josevaltersilvacarneiro\Html\Src\Classes\Container
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  https://www.gnu.org/licenses/quick-guide-gplv3.html GPLv3
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Classes/Container
 */

namespace Josevaltersilvacarneiro\Html\Src\Classes\Container;

use Josevaltersilvacarneiro\Html\Src\Classes\Exceptions\ContainerException;
use Josevaltersilvacarneiro\Html\Src\Classes\Exceptions\NotFoundException;
use Josevaltersilvacarneiro\Html\App\Controller\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * This class is defined in the PSR-11 standard.
 * 
 * @var array<string,Controller> $_container Array of namespaces for the controllers
 * 
 * @category  Container
 * @package   Josevaltersilvacarneiro\Html\Src\Classes\Container
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.2.0
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Classes/Container
 * @see       https://www.php-fig.org/psr/psr-11/
 */
class Container implements ContainerInterface
{
    private readonly array $_container = [];

    /**
     * This method adds a new entry to the container.
     * 
     * @param string        $className    Name of the class to be instantiated
     * @param array<string> $dependencies Dependencies to be injected
     * 
     * @return void
     * @throws ContainerException class doesn't exist or doesn't have a fork method
     */
    public function add(string $className, array $dependencies = []): void
    {
        $dependencies = array_map(
            function ($dependency) {
                if (! class_exists($dependency)) {
                    throw new ContainerException(
                        "Class {$dependency} not found",
                        1000
                    );
                }

                $reflectDependency = new \ReflectionClass($dependency);

                if (!$reflectDependency->hasMethod('fork')) {
                    throw new ContainerException(
                        "Class {$dependency} hasn't a fork method",
                        1000
                    ); 
                }

                return $dependency::fork();
            },
            $dependencies
        );

        try {
            $this->_container[$className] = function ($container) use (
                $className, $dependencies
            ) {
                return new $className(...$dependencies);
            };
        } catch (\Error $e) {
            throw new ContainerException(
                "Error while retrieving the entry {$className}", 10, $e
            );
        }
    }

    /**
     * This method is defined in the PSR-11 standard.
     * 
     * @param string $id Indentifier of the object to be instantiated
     * 
     * @return mixed Entry
     * @throws NotFoundExceptionInterface No entry was found for this identifier
     * @throws ContainerExceptionInterface Error while retrieving the entry
     */
    public function get(string $id): mixed
    {
        if (!$this->has($id)) {
            throw new NotFoundException("{$id} not found in container");
        }

        return $this->_container[$id]($this);
    }

    /**
     * This method is defined in the PSR-11 standard.
     * 
     * @param string $id Indentifier of the object to be instantiated
     * 
     * @return bool true if can return an entry for the given id; false otherwise
     */
    public function has(string $id): bool
    {
        return isset($this->_container[$id]);
    }
}
