<?php

/**
 * This package is responsible for accessing
 * the database.
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
 * @package     Josevaltersilvacarneiro\Html\Src\Classes\Sql
 */

namespace Josevaltersilvacarneiro\Html\Src\Classes\Sql;

use Josevaltersilvacarneiro\Html\Src\Classes\Log\PDOLog;
use \PDO;
use \PDOException;

/**
 * This class contains the method that creates
 * the database connection.
 * 
 * @var PDO $conn the database connection
 * 
 * @method void connectDB() sets up the database connection
 * 
 * @author José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version 0.3
 * @abstract
 * @see https://www.php.net/manual/en/book.pdo.php
 * @copyright Copyright (C) 2023, José V S Carneiro
 * @license GPLv3
 */

abstract class Connect
{
	protected PDO $conn;

	protected function connectDB()
	{
		try {
			$this->conn = new PDO(_DSN, _USER, _PASS);
		} catch (PDOException $e) {
			$log = new PDOLog();
			$log->setFile(__FILE__);
			$log->setLine($e->getLine());
			$log->store("It wasn't possible access the database");

			throw new ConnectException($e);
		}
	}
}
