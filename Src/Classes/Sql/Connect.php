<?php

declare(strict_types=1);

/**
 * This package is responsible for accessing the database.
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
 * @package Josevaltersilvacarneiro\Html\Src\Classes\Sql
 */

namespace Josevaltersilvacarneiro\Html\Src\Classes\Sql;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Sql\ConnectInterface;

/**
 * Through specific methods, this class returns connections to different databases.
 * 
 * @author José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version 0.4
 * @see https://www.php.net/manual/en/book.pdo.php
 * @copyright Copyright (C) 2023, José V S Carneiro
 * @license GPLv3
 */
class Connect implements ConnectInterface
{
	public static function newMysqlConnection(): \PDO
	{
		try {
			return new \PDO(_DSN, _USER, _PASS);
		} catch (\PDOException $e) {
			$e = new ConnectException($e);
			$e->storeLog();
			throw $e;
		}
	}
}
