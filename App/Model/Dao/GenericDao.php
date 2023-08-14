<?php

declare(strict_types=1);

/**
 * The Dao (Data Access Object) package is a crucial component of the
 * application responsible for handling the interaction between the
 * application and the underlying database. It provides a set of classes
 * that encapsulate database operations, such as data retrieval, insertion,
 * update, and deletion, for various entities or tables in the system.
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
 * @package     Josevaltersilvacarneiro\Html\App\Model\Dao
 */

namespace Josevaltersilvacarneiro\Html\App\Model\Dao;

use Josevaltersilvacarneiro\Html\Src\Classes\Sql\SanitizeSql;

/**
 * This class makes CRUD - creates, reads, updates and deletes.
 * 
 * @var string $_TABLE table's name where the operations will be performed
 * 
 * @method bool			c(array $register)	creates a new register
 * @method array|false	r(array $register)	reads a register
 * @method bool			u(array $register)	updates $register
 * @method bool			d(array $register)	deletes $register
 * 
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.3
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */
class GenericDao extends SanitizeSql
{
	public function __construct(private string $_TABLE)
	{
		parent::__construct();
	}

	/**
	 * This method tries to create a new register and returns true if succeeds;
	 * false otherwise.
	 * 
	 * @param array $register @example array('foo' => 'foobar', 'foobar' => 'foo')
	 * 
	 * @return bool true on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.3
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.list
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
	public function c(array $register): bool
	{
		$record = parent::cleanCreate($this->_TABLE, $register);
		if ($record === false) return false;
		// failed

		list($query, $record) = $record;
		$stmt = $this->query($query, $record);

		return $stmt !== false && $stmt->rowCount() > 0;
	}

	/**
	 * This method tries to read the others fields of $register and returns them
	 * if succeeds; false otherwise.
	 * 
	 * @param array $register @example array('fooID' => 'foobar')
	 * 
	 * @return array|false $register on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.3
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.list
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
	public function r(array $register): array|false
	{
		$record = parent::cleanRead($this->_TABLE, $register);
		if ($record === false) return false;
		// failed

		list($query, $record) = $record;
		$stmt = $this->query($query, $record);

		return $stmt === false || $stmt->rowCount() < 1 ?
			false :
			$stmt->fetch(\PDO::FETCH_ASSOC);
	}

	/**
	 * This method tries to update $register and returns true if succeeds;
	 * false otherwise.
	 * 
	 * @param array $register @example array('fooID' => 'bar', 'foobar' => 'foo')
	 * 
	 * @return bool true on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.3
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.list
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
	public function u(array $register): bool
	{
		$record = parent::cleanUpdate($this->_TABLE, $register);
		if ($record === false) return false;
		// failed

		list($query, $record) = $record;
		$stmt = $this->query($query, $record);

		return $stmt !== false && $stmt->rowCount() > 0;
	}

	/**
	 * This method tries to delete $register and returns true if succeeds;
	 * false otherwise.
	 * 
	 * @param array $register @example array('fooID' => 'bar')
	 * 
	 * @return bool true on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.3
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.list
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
	public function d(array $register): bool
	{
		$record = parent::cleanDelete($this->_TABLE, $register);
		if ($record === false) return false;
		// failed

		list($query, $record) = $record;
		$stmt = $this->query($query, $record);

		return $stmt !== false && $stmt->rowCount() > 0;
	}

	/**************************************************************/
	/**************************************************************/
	// IMPROVED                                              CRUD //

	/**
	 * This method is responsible for inserting a record into the database
	 * and returning the last inserted ID if the insertion is successful.
	 * 
	 * @param		array $record @example array('fooID' => 'bar', 'foo' => 'bar')
	 * 
	 * @return		string|false ID inserted on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function ic(array $record): string|false
	{
		return $this->c($record) ? $this->getLastInsertedId() : false;
	}
}
