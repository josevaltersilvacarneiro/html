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

/**
 * This trait offers functions that parses
 * the url.
 *
 * @method	array getUrl() returns a array with each string between DIRECTORY_SEPARATOR
 *
 * @author	José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version	0.1
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license	GPLv3
 */

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
	 * @version	0.1.1
	 * @access	protected
	 * @see		https://datatracker.ietf.org/doc/html/rfc1738
	 * @see		https://datatracker.ietf.org/doc/html/rfc1630
	 * @see		https://www.ibm.com/docs/en/cics-ts/5.2?topic=concepts-components-url
	 */

	protected function getUrl(): array
	{
		/**
		 * @see	.htaccess in \
		 * @see	https://www.php.net/manual/en/reserved.variables.get.php
		 * @see	https://www.php.net/manual/en/function.rtrim.php
		 * @see	https://www.php.net/manual/en/function.explode.php
		 */

		return explode(
			'/',	# RFCs 1630, 1738 - [...] The slash ("/", ASCII 2F hex)
				# character is reserved for the delimiting [...]
			rtrim($_GET['url']),
			FILTER_SANITIZE_URL
		);
	}
}
