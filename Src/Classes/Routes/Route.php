<?php

/**
 * This package is reponsible for providing the route.
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
 * @var	string DEFAULT_ROUTE	sets up a route when the user doesn't specify
 * 
 * @author	José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version	0.2
 * @copyright Copyright (C) 2023, José V S Carneiro
 * @license	GPLv3
 */

class Route
{
	use TraitUrlParser;

	private const DEFAULT_PAGE 	= 'Home';
	private static array $resources = array();

	public function __construct()
	{
		self::$resources = $this->getUrl();
	}

	/**
	 * This function finds all available routes in __CONTROLLER__
	 * and returns them.
	 *
	 * @return array<string> available routes
	 *
	 * @author	José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version	0.2
	 * @access	public
	 * @see		https://www.php.net/manual/en/class.directoryiterator.php
	 */
	public static function getAvailableRoutes(): array
	{
		$dir = new DirectoryIterator(__CONTROLLER__);

		$routes = [];
		foreach ($dir as $filename) {
			if ($filename->isDir() && !$filename->isDot())
				array_push($routes, $filename->getFilename());
		}

		return $routes;
	}

	/**
	 * @return string Namespace of the page controller
	 *
	 * @author	José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @author	0.1
	 * @access	public
	 * @see		https://www.php.net/manual/en/function.count.php
	 */
	public function getControllerNamespace(): string
	{
		$page = count(self::$resources) === 0 || !in_array(mb_convert_case(self::$resources[0], MB_CASE_TITLE),
			$this->getAvailableRoutes()) ? self::DEFAULT_PAGE : mb_convert_case(self::$resources[0], MB_CASE_TITLE);

		return 'Josevaltersilvacarneiro\\Html\\App\\Controller\\' .
			$page . '\\' . $page;
	}

	/**
	 * @return string Service name on success; false otherwise 
	 */
	public function getService(): string|false
	{
		return self::$resources[1] ?? false;
	}

	/**
	 * @return array<string> on success; empty array otherwise
	 */
	public function getParameters(): array
	{
		return array_slice(self::$resources, 2);
	}
}
