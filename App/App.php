<?php

declare(strict_types=1,encoding="UTF-8");

/**
 * This package is responsible for initializing
 * the service.
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

use Josevaltersilvacarneiro\Html\Src\Classes\Routes\Route;
use Josevaltersilvacarneiro\Html\App\Controller\Controller;
use Josevaltersilvacarneiro\Html\Src\Classes\Log\RequestLog;

/**
 * This class creates the controller object,
 * calls the method and passes the parameters
 * appropriately.
 *
 * @var Controller    $obj        the controller - for example, Home
 * @var string        $method        method that belongs to the $obj
 * @var string        $parameters    parameters of the $method
 *
 * @method void addController()    sets up the controller object
 * @method void addMethod()        sets up a method in $obj
 * @method void addParameters()    sets up the params in $method
 *
 * @author    José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version   0.5
 * @see       Josevaltersilvacarneiro\Html\Src\Classes\Routes\Route
 * @copyright Copyright (C) 2023, José V S Carneiro
 * @license   GPLv3
 */

final class App extends Route
{
    private Controller    $obj;
    private ?string        $method;
    private array        $parameters = array();

    /**
     * Initializes the object.
     * 
     * @return void
     * 
     * @author    José V S Carneiro <git@josevaltersilvacarneiro.net>
     * @version   0.3
     * @access    public
     * @see       https://www.php.net/manual/en/function.is-null.php
     * @see       https://www.php.net/manual/en/function.method-exists.php
     * @see       https://www.php.net/manual/en/function.call-user-func-array.php
     * @copyright Copyright (C) 2023, José V S Carneiro
     * @license   GPLv3
     */

    public function __construct()
    {
        $this->addController();
        $this->addMethod();
        $this->addParameters();
        
        try {
            if (!is_null($this->getMethod()) 
                && method_exists($this->obj, $this->getMethod())
            ) {

                // if the method isn't null and exists
                // in the controller, call it

                call_user_func_array(
                    [
                    $this->obj,
                    $this->getMethod()
                    ],
                    $this->getParameters()
                );
            }
        } catch (\BadMethodCallException $e) {

            // if an exception was thrown here, then
            // the application user made a terrible
            // request. IT COULD BE A HACKER ATTACK
            
            $log = new RequestLog();
            $log->setFile(__FILE__);
            $log->setLine(__LINE__);

            $user = $this->obj->getSession()->getUser()->getFullname();

            $log->store("The user ${user} made a bad request # " . $e->getMessage());
        }

        $this->obj->renderLayout();
    }

    public function setMethod(?string $method): void
    {
        $this->method = $method;
    }

    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * This method instantiates the CONTROLLER dynamically
     * according to the service that the user requested.
     * The route property returns this service.
     * 
     * @return void
     * 
     * @author    José V S Carneiro <git@josevaltersilvacarneiro.net>
     * @version   0.1
     * @access    private
     * @see       https://www.php.net/manual/en/language.oop5.basic.php#language.oop5.basic.new
     * @copyright Copyright (C) 2023, José V S Carneiro
     * @license   GPLv3
     */

    private function addController(): void
    {
        $className = 'Josevaltersilvacarneiro\\Html\\App\\Controller\\' .
        $this->route . '\\' . $this->route;
        
        $this->obj = new $className;
    }

    /**
     * This method sets the second parameter
     * passed in url as method in $this->obj.
     * 
     * @return void
     * 
     * @author    José V S Carneiro <git@josevaltersilvacarneiro.net>
     * @version   0.2
     * @access    private
     * @see       https://www.php.net/manual/en/migration70.new-features.php#migration70.new-features.null-coalesce-op
     * @copyright Copyright (C) 2023, José V S Carneiro
     * @license   GPLv3
     */

    private function addMethod(): void
    {
        $methodName = $this->url[1] ?? null;
        
        $this->setMethod(method: $methodName);
    }

    /**
     * This method sets the parameters passed
     * by the user in url.
     * 
     * @return void
     * 
     * @author    José V S Carneiro <git@josevaltersilvacarneiro.net>
     * @version   0.2
     * @access    private
     * @see       https://www.php.net/manual/en/function.array-slice.php
     * @copyright Copyright (C) 2023, José V S Carneiro
     * @license   GPLv3
     */

    private function addParameters(): void
    {
        $parameters = array_slice($this->url, 2);
        $this->setParameters($parameters);
    }
}
