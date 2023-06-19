<?php

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
 * @package     Josevaltersilvacarneiro\Html\App
 */

namespace Josevaltersilvacarneiro\Html\App;

use Josevaltersilvacarneiro\Html\App\Controller\HTMLController;
use Josevaltersilvacarneiro\Html\App\Controller\JSONController;

use Josevaltersilvacarneiro\Html\Src\Classes\Routes\Route;
use Josevaltersilvacarneiro\Html\App\Controller\Controller;

/**
 * This class creates the controller object,
 * calls the method and passes the parameters
 * appropriately.
 *
 * @var Controller	$obj		the controller - for example, Home
 * @var string		$method		method that belongs to the $obj
 * @var string		$parameters	parameters of the method $method
 *
 * @method void addController()	sets up the controller object
 * @method void addMethod()		sets up a method in $obj
 * @method void addParameters()	sets up the params in $method
 *
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.2
 * @see			Josevaltersilvacarneiro\Html\Src\Classes\Routes\Route
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */

class Dispatch extends Route
{
	private	HTMLController|JSONController	$obj;
	private	string		$method;
	private	array		$parameters = array();

	public function __construct()
	{
		self::addController();
		self::addParameters();
		self::addMethod();

		$this->obj->renderLayout();
	}

	public function setMethod(string $method): void
	{
		$this->method = $method;
	}

	public function setParameters(array $parameters): void
	{
		$this->parameters = $parameters;
	}

	public function getMethod(): string
	{
		return $this->method;
	}

	public function getParameters(): array
	{
		return $this->parameters;
	}

	/**
	 * This method calls a controller dynamically according
	 * to the service that the user requested. The route
	 * property returns this service.
	 * 
	 * @return	void
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		private
	 * @see			https://www.php.net/manual/en/language.oop5.basic.php#language.oop5.basic.new
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	private function addController(): void
	{
		$className = 'Josevaltersilvacarneiro\\Html\\App\\Controller\\' .
			$this->route . '\\' . $this->route;
		
		$this->obj = new $className;
	}

	/**
	 * This method checks if the second parameter
	 * passed in url is an existing method in
	 * $this->obj and calls it if true; otherwise
	 * just returns.
	 * 
	 * @return		void
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		private
	 * @see			https://www.php.net/manual/en/function.isset.php
	 * @see			https://www.php.net/manual/en/function.method-exists.php
	 * @see			https://www.php.net/manual/en/function.call-user-func-array.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	private function addMethod(): void
	{		
		if (
			!isset($this->url[1]) || 
			!method_exists($this->obj, $this->url[1])
		)
			return ; /* if the method doesn't exist, return */
		
		$this->setMethod($this->url[1]);
		
		call_user_func_array(
			[
				$this->obj,
				$this->getMethod()
			],
			$this->getParameters()
		);
	}

	/**
	 * This method checks if more than two parameters
	 * were passed in url and sets them if true;
	 * otherwise, just returns.
	 * 
	 * @return		void
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		private
	 * @see			https://www.php.net/manual/en/function.count.php
	 * @see			https://www.php.net/manual/en/function.array-slice.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	private function addParameters(): void
	{
		$length = count($this->url);

		if ($length <= 2)
			return ; /* if no more than two parameters are passed
						in the url, return */

		$parameters = array_slice($this->url, 2);
		$this->setParameters($parameters);
	}
}
