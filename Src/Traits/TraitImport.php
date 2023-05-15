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
 * @package     Josevaltersilvacarneiro\Html\Src\Traits
 */

namespace Josevaltersilvacarneiro\Html\Src\Traits;

/**
 * This trait offers functions that handles
 * the required files.
 *
 * @method	bool import(string $filename) 					includes the file
 * @method	void importPage(string $path, string $dir, string $filename)	tries to include a file
 *
 * @author	José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version	0.3
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license	GPLv3
 */

trait TraitImport
{
	/**
         * This function includes the file $filename
	 * and returns true if it exists, it's a re-
	 * gular file and can be read; otherwise re-
	 * turns false.
         *
         * @param       string  $filename path of the file to be included
         * @return      bool
         *
         * @author      José V S Carneiro <git@josevaltersilvacarneiro.net>
         * @version     0.3
         * @access      protected
         * @see         https://www.php.net/manual/en/function.is-file.php
         * @see         https://www.php.net/manual/en/function.is-readable.php
         */

	protected function import(string $filename): bool
        {
                if (is_file($filename) && is_readable($filename)) {
			require_once $filename;
			return true;
		}

		return false;
	}

	/**
	 * This function tries to include a file
	 * according to the $path and $dir pas-
	 * sed as arguments.
	 *
	 * @param	string	$path	main path
	 * @param	string	$dir	specific path
	 * @param	string	$filename
	 * @return	void
	 *
	 * @author	José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version	0.3
	 * @access	protected
	 */

	protected function importPage(string $path, string $dir, string $filename): void
        {
		if (!$this->import($path . $dir . $filename))
			$this->import($path . $filename);
        }
}
