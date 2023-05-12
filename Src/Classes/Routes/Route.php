<?php

/**
 * This package is reponsible for providing
 * the route.
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
 * @package	Src\Classes\Routes
 */

namespace Josevaltersilvacarneiro\Html\Src\Classes\Routes;

use Josevaltersilvacarneiro\Html\Src\Traits\TraitUrlParser;
use \DirectoryIterator;

/**
 * This class handles the routes. However, it
 * must be extended by the Dispatch.
 *
 * @var	const	DEFAULT_ROUTE	sets up a route when the user doesn't specify
 * @var	const	ERROR_ROUTE	sets up an error route when the route specified doesn't exist
 *
 * @method	array	availableRoutes()	returns the available routes according to the controllers
 * @method	string	getRoute()		returns the route invoked by the user
 *
 * @author	José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version	0.1
 * @abstract
 * @see		App\Dispatch
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license	GPLv3
 */

abstract class Route
{
	use TraitUrlParser;

	private const DEFAULT_ROUTE 	= 'Home';
	private const ERROR_ROUTE 	= 'Error';

	/**
	 * This function finds all available routes in __CONTROLLER__
	 * and returns them.
	 *
	 * @return	array	available routes
	 *
	 * @author	José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version	0.1
	 * @access	public
	 * @see		https://www.php.net/manual/en/class.directoryiterator.php
	 */

	public static function availableRoutes(): array
	{
		$routes = array();

		$dir = new DirectoryIterator(__CONTROLLER__);

		foreach ($dir as $filename)

			if ($filename->isDir() && !$filename->isDot())
				array_push($routes, $filename->getFilename());

		return $routes;
	}

	/**
	 * This function returns the route specified by the user
	 * or self::ERROR_ROUTE if the invoked route doesn't exist.
	 *
	 * @return	string	the route, with the first letter capitalized
	 *
	 * @author	José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @author	0.1
	 * @access	public
	 * @see		https://www.php.net/manual/en/function.in-array.php
	 */

	public function getRoute(): string
	{
		/**
		 * $service gets the route specified by the user.
		 *
		 * For example, if the user requests
		 * __URL__/service/method/param1/param2, $service
		 * gets Service.
		 *
		 * @see	Src\Traits\TraitUrlParser
		 * @see	https://www.php.net/manual/en/function.ucfirst.php
		 */

		$service = ucfirst(
			$this->getUrl()[0]
		);

		if (empty($service))
			return self::DEFAULT_ROUTE; // the user didn't specify a route

		$routes = self::availableRoutes();

		return in_array($service, $routes, true) ? $service : self::ERROR_ROUTE;
	}
}
