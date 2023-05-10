<?php

/**
 * This package is responsible for offering
 * useful functions.
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
 * @package     Src\Traits
 */

namespace Src\Traits;

trait TraitUrlParser
{
	/**
	 * This method returns a array containing
	 * the url arguments.
	 * 
	 * For example:
	 *
	 * http://test.com/foo/bar => array {
	 * 	[0] => string(3) "foo",
	 * 	[1] => string(3) "bar",
	 * }
	 *
	 * @return	array
	 *
	 * @author	José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version	0.1
	 * @access	protected
	 * @see		https://www.php.net/manual/en/function.rtrim.php
	 * @see		https://www.php.net/manual/en/function.explode.php
	 */

	protected function getUrl(): array
	{
		return explode('/', rtrim($_GET['url']), FILTER_SANITIZE_URL);
	}
}
