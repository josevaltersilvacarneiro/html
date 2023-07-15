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

use Josevaltersilvacarneiro\Html\Src\Classes\Sql\DatabaseStandard;
use Josevaltersilvacarneiro\Html\Src\Classes\Sql\Sql;

/**
 * This class makes CRUD - creates, reads,
 * updates and deletes - in the database.
 *
 * @var string		$_TABLE		table's name where the operations will be performed
 * 
 * @var array		$primaryKeys			each element is a PRIMARY KEY of this table
 * @var array		$primaryKeysIncremented	each element is a PRIMARY KEY AUTO_INCREMENT
 * @var array		$uniqueIdentifiers		all elements cannot be repeated
 * @var array		$requiredColumns		columns required when a new register added
 *
 * @method void addPrimaryKeys()			sets up $primaryKeys
 * @method void addPrimaryKeysIncremented()	sets up $primaryKeysIncremented
 * @method void addUniqueIdentifiers()		sets up $uniqueIdentifiers
 * @method void addRequiredColumns()		sets up $requiredColumns
 * 
 * @method bool			c(array $register)	creates a new register
 * @method array|false	r(array $register)	reads a register
 * @method bool			u(array $register)	updates $register
 * @method bool			d(array $register)	deletes $register
 *
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.1
 * @see			Josevaltersilvacarneiro\Html\Src\Classes\Sql\Sql
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */

class GenericDao extends Sql
{
	private array $primaryKeys				= array();
	private array $primaryKeysIncremented	= array();
	private array $uniqueIdentifiers		= array();
	private array $requiredColumns			= array();

	public function __construct(private string $_TABLE)
	{
		parent::__construct();

		$this->addPrimaryKeys();
		$this->addPrimaryKeysIncremented();
		$this->addUniqueIdentifiers();
		$this->addRequiredColumns();
	}

	/**
	 * This method sets up the PRIMARY KEY
	 * from $this->_TABLE table.
	 * 
	 * @return		void
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		private
	 * @see			https://www.php.net/manual/en/function.array-walk.php
	 * @see			https://www.php.net/manual/en/function.array-push.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	private function addPrimaryKeys(): void
	{
		$query = DatabaseStandard::generatePrimaryKeysStandard(table: 'tb');

		$stmt = $this->query($query,
			array('tb' => $this->_TABLE));

		if ($stmt !== false) {

			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

			// a return example:
			//
			// 	$result = array(
			// 		array('COLUMN_NAME') => 'fooID',
			// 		array('COLUMN_NAME') => 'fooBAR',
			// 		array('COLUMN_NAME') => 'fooFOO',
			// 	)
			
			array_walk($result, function ($value, $_) {
				array_push($this->primaryKeys, $value['COLUMN_NAME']);

				// an anonymous function adds very
				// returned column to $this->primaryKeys
			});

			// the array_walk function applies this operation
			// to all elements of the array $result.
			//
			// in the end, $this->primaryKeys is equal to
			// 	array(
			//		'fooID',
			// 		'fooBAR',
			// 		'fooFOO'
			// 	)
		}
	}

	/**
	 * This method sets up the PRIMARY KEY AUTO_INCREMENT
	 * from $this->_TABLE table.
	 * 
	 * @return		void
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		private
	 * @see			GenericDao::addPrimaryKeys(): void
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	private function addPrimaryKeysIncremented(): void
	{
		$query = DatabaseStandard::generatePrimaryKeysIncremented(table: 'tb');

		$stmt = $this->query($query,
			array('tb' => $this->_TABLE));

		if ($stmt !== false) {

			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			
			array_walk($result, function ($value, $_) {
				array_push($this->primaryKeysIncremented, $value['COLUMN_NAME']);
			});
		}
	}

	/**
	 * This method sets up the columns with UNIQUE CONSTRAINT
	 * from $this->_TABLE table.
	 * 
	 * @return		void
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		private
	 * @see			GenericDao::addPrimaryKeys(): void
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	private function addUniqueIdentifiers(): void
	{
		$query = DatabaseStandard::generateUniqueIdentifiers(table: 'tb');

		$stmt = $this->query($query,
			array('tb' => $this->_TABLE));

		if ($stmt !== false) {

			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			
			array_walk($result, function ($value, $_) {
				array_push($this->uniqueIdentifiers, $value['COLUMN_NAME']);
			});
		}
	}

	/**
	 * This method sets up the required columns
	 * from $this->_TABLE table.
	 * 
	 * @return		void
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		private
	 * @see			GenericDao::addPrimaryKeys(): void
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	private function addRequiredColumns(): void
	{
		$query = DatabaseStandard::generateRequiredColumns(table: 'tb');

		$stmt = $this->query($query,
			array('tb' => $this->_TABLE));

		if ($stmt !== false) {

			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			
			array_walk($result, function ($value, $_) {
				array_push($this->requiredColumns, $value['COLUMN_NAME']);
			});
		}
	}

	/**
	 * This method returns an array with the values
	 * of $dict where the keys are elements of one
	 * of the arrays passed as argument in $lists.
	 * 
	 * @param		array	$dict	array of type key-value - or dictionary
	 * @param		array	$lists	variable length argument list
	 * 
	 * @return		array	@example array('fooID' => 'foo', 'fooBAR' => 'bar')
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		private
	 * @see			https://www.php.net/manual/en/function.array-merge.php
	 * @see			https://www.php.net/manual/en/function.array-filter.php
	 * @see			https://www.php.net/manual/en/function.in-array.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	private static function array_key_diff(array $dict, array ...$lists): array
	{
		$list = array_merge(...$lists);

		return array_filter($dict, function(string $key) use ($list) {
				return in_array($key, $list);
			}, ARRAY_FILTER_USE_KEY);
	}

	public function getPrimaryKeys(): array
	{
		return $this->primaryKeys;
	}

	public function getPrimaryKeysIncremented(): array
	{
		return $this->primaryKeysIncremented;
	}

	public function getUniqueIdentifiers(): array
	{
		return $this->uniqueIdentifiers;
	}

	public function getRequiredColumns(): array
	{
		return $this->requiredColumns;
	}

	/**
	 * This method tries to create a new register
	 * and returns true if succeeds; otherwise
	 * returns false.
	 * 
	 * @param		array $register @example array('foo' => 'foobar', 'foobar' => 'foo')
	 * 
	 * @return		bool
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.array-keys.php
	 * @see			https://www.php.net/manual/en/function.array-diff-assoc.php
	 * @see			https://www.php.net/manual/en/function.array-diff.php
	 * @see			https://www.php.net/manual/en/function.empty.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
	
	public function c(array $register): bool
	{
		// each key in the $register represents
		// a column in the database table; each
		// value represents the corresponding
		// value for that register.
		// for example, in array('foo' => 'bar')
		// foo is the field (or column) and bar
		// is its corresponding value.

		$columns		= array_keys($register);

		// $columns contains all columns (or fields)
		// of $register.
		// for example, if $register is equal to
		// array('foo' => 'bar'), $columns is equal
		// to array('foo').

		$required		= array_diff_assoc(
			$columns, $this->getPrimaryKeysIncremented());

		// $required contains all columns of $columns
		// except PRIMARY KEY auto incremented.

		if (!empty(array_diff(
			$this->getRequiredColumns(), $required))) return false;

		// the above statement returns false if
		// the fields required to create a new
		// register in the database weren't
		// passed in $register.

		$query			= DatabaseStandard::generateCreateStandard(
			$this->_TABLE, $required);

		// $query gets a sql query to CREATE a
		// new register in the database.

		$cleanRegister	= self::array_key_diff($register, $required);

		// only the fields that were passed in to
		// generate the query should be passed to
		// Sql::query(string $query, array $parameters = array()): \PDOStatement
		// $cleanRegister contains all values of
		// $register that are in $required, i.e.
		// $cleanRegister = $register ∩ $required

		$stmt			= $this->query($query, $cleanRegister);
		
		return $stmt !== false && $stmt->rowCount() > 0;
	}

	/**
	 * This method tries to read the others 
	 * fields of $register and returns them
	 * if succeeds; otherwise returns false.
	 * 
	 * @param		array $register @example array('fooID' => 'foobar')
	 * 
	 * @return		array|false
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.array-keys.php
	 * @see			https://www.php.net/manual/en/function.array-intersect.php
	 * @see			https://www.php.net/manual/en/function.empty.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function r(array $register): array|false
	{
		// $register must have at least one field
		// with UNIQUE CONSTRAINT.
		// for example, if $this->_TABLE is equal
		// to 'tbFoo', then $register can be as
		// array('fooID' => 'bar').

		$columns		= array_keys($register);

		// see GenericDao::c(array $register): bool
		// to understand the above statement.

		$identifiers	= array_intersect(
			$this->getUniqueIdentifiers(), $columns);

		// $identifiers gets all unique identifiers
		// that were passed in $register.

		if (empty($identifiers)) return false;

		// if no unique identifier was passed,
		// return because the operation cannot
		// be completed.

		$query			= DatabaseStandard::generateReadStandard(
			$this->_TABLE, $identifiers);

		// $query gets a sql query to READ a register
		// in the database.

		$cleanRegister	= self::array_key_diff($register, $identifiers);

		// see GenericDao::c(array $register): bool
		// to understand the above statement.

		$stmt			= $this->query($query, $cleanRegister);

		return $stmt === false || $stmt->rowCount() < 1 ?
			false :
			$stmt->fetch(\PDO::FETCH_ASSOC);
	}

	/**
	 * This method tries to update $register
	 * and returns true if succeeds; otherwise
	 * returns false.
	 * 
	 * @param		array $register @example array('fooID' => 'bar', 'foobar' => 'foo')
	 * 
	 * @return		bool
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.array-keys.php
	 * @see			https://www.php.net/manual/en/function.array-intersect.php
	 * @see			https://www.php.net/manual/en/function.array-diff-assoc.php
	 * @see			https://www.php.net/manual/en/function.empty.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function u(array $register): bool
	{
		// $register must have at least one field with
		// UNIQUE CONSTRAINT and must have at least
		// one field that can be updated.
		// for example, if $this->_TABLE is equal to
		// 'tbFoo', then $register can be as `array(
		// 'fooID' => 'bar', 'fooFOO' => 'foobar')`,
		// where the fooFOO field doesn't have UNIQUE
		// CONSTRAINT or there's no other register in
		// the table with value 'foobar' for fooFOO.
		//
		// p.s. PRIMARY KEY cannot be updated

		$columns		= array_keys($register);

		// see GenericDao::c(array $register): bool
		// to understand the above statement.

		$identifiers	= array_intersect(
			$this->getPrimaryKeys(), $columns); // err

		// see GenericDao::c(array $register): bool
		// to understand the above statement.

		$required		= array_diff_assoc(
			$columns, $this->getPrimaryKeys()); // doc

		// see GenericDao::c(array $register): bool
		// to understand the above statement.

		if (empty($identifiers) || empty($required)) return false;

		// if no unique identifier was passed or no
		// field was passed on to be update, return
		// because the operation cannot be completed.

		$query = DatabaseStandard::generateUpdateStandard(
			$this->_TABLE, uniqueIdentifiers: $identifiers, columns: $required);

		// $query gets a sql query to UPDATE a register
		// in the database.

		$cleanRegister	= self::array_key_diff($register, $identifiers, $required);

		// see GenericDao::c(array $register): bool
		// to understand the above statement.
		// $cleanRegister = $register ∩ ($identifiers ∪ $required)

		$stmt			= $this->query($query, $cleanRegister);

		return $stmt !== false && $stmt->rowCount() > 0;
	}

	/**
	 * This method tries to delete $register
	 * and returns true if succeeds; otherwise
	 * returns false.
	 * 
	 * @param		array $register @example array('fooID' => 'bar')
	 * 
	 * @return		bool
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.array-keys.php
	 * @see			https://www.php.net/manual/en/function.array-intersect.php
	 * @see			https://www.php.net/manual/en/function.empty.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function d(array $register): bool
	{
		$columns		= array_keys($register);

		// see GenericDao::c(array $register): bool
		// to understand the above statement.

		$identifiers	= array_intersect(
			$this->getPrimaryKeys(), $columns);

		// see GenericDao::u(array $register): bool
		// to understand the above statement.

		if (empty($identifiers)) return false;

		// see GenericDao::r(array $register): array|false
		// to understand the above statement.

		$query			= DatabaseStandard::generateDeleteStandard(
			$this->_TABLE, uniqueIdentifiers: $identifiers);

		// $query gets a sql query to DELETE a register
		// in the database.

		$cleanRegister	= self::array_key_diff($register, $identifiers);

		// see GenericDao::c(array $register): bool
		// to understand the above statement.

		$stmt			= $this->query($query, $cleanRegister);

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
