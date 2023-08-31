<?php

declare(strict_types=1);

/**
 * This package is responsible for accessing the database.
 * PHP VERSION >= 8.2.0
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
 * @category Sql
 * @package  Josevaltersilvacarneiro\Html\Src\Classes\Sql
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Classes/Sql
 */

namespace Josevaltersilvacarneiro\Html\Src\Classes\Sql;

use Josevaltersilvacarneiro\Html\Src\Classes\Sql\DatabaseStandard;
use Josevaltersilvacarneiro\Html\Src\Classes\Sql\Sql;

/**
 * This class sanitizes the CRUD.
 * 
 * @staticvar array $TABLES stores the most important information about the tables
 * 
 * @method array|false cleanCreate(array $record) to create
 * @method array|false cleanRead(array $record)	to read
 * @method array|false cleanUpdate(array $record) to update
 * @method array|false cleanDelete(array $record) to delete
 * 
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.2
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */
abstract class SanitizeSql extends Sql
{
	/**
	 * TABLES = [
	 * 	'example_table' => [
	 * 		'primary_key' => 'example_key',
	 * 		...
	 * 	],
	 * 	'other_example_table' => [
	 * 		'primary_key' => 'other_example_key',
	 * 		...
	 * 	]
	 * ]
	 */
	private static array $TABLES = [];

	/**
	 * Initializes the SanitizeSql.
	 * 
	 * @param \PDO $conn PDO instance
	 */
	public function __construct(\PDO $conn)
	{
		parent::__construct($conn);
	}

	/**
	 * This method returns a tuple where the first element is a query to
	 * create the second element that is a record.
	 * 
	 * @param string $table	table's name
	 * @param array	$record	record to be created
	 * 
	 * @return array|false tuple on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		protected
	 * @see			https://www.php.net/manual/en/function.empty
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
	protected function cleanCreate(string $table, array $record): array|false
	{
		if (!$this->mapTable($table) || !$this->areTypesValid($record))
			return false;

		if (!empty(array_diff(
			self::$TABLES[$table]['required_columns'], array_keys($record))))
			return false;

		// the above statement returns false if
		// the required fields to create a new
		// record weren't passed in $record

		$primaryKey = self::$TABLES[$table]['primary_key'];
		if (!$this->isPrimaryKeyRequired($table) &&
			array_key_exists($primaryKey, $record))
			unset($record[$primaryKey]);
		// if the primary key was passed as parameter, but it isn't
		// required, delete it

		$record = self::array_key_diff($record, self::$TABLES[$table]['columns']);
		// keys that aren't part of the table's columns must be deleted

		$query = DatabaseStandard::generateCreateStandard($table,
			array_keys($record));
		// $query gets a sql query to CREATE a new record

		return [
			$query,
			$record
		];
	}

	/**
	 * This method returns a tuple where the first element is a query to
	 * read the second element that is a record.
	 * 
	 * @param string $table	table's name
	 * @param array	$record	record to be read
	 * 
	 * @return array|false tuple on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		protected
	 * @see			https://www.php.net/manual/en/function.empty
	 * @see			https://www.php.net/manual/en/function.array-key-first
	 * @see			https://www.php.net/manual/en/function.array-slice
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
	protected function cleanRead(string $table, array $record): array|false
	{
		if (!$this->mapTable($table) || !$this->areTypesValid($record))
			return false;

		$record = self::array_key_diff($record,
			self::$TABLES[$table]['unique_constraints']);
		// columns that aren't unique constraints must be deleted

		if (empty($record)) return false;
		// unable to read empty record

		$query = DatabaseStandard::generateReadStandard($table,
			array_key_first($record));
		// $query gets the sql query to READ a record

		return [
			$query,
			array_slice($record, 0, 1)
		];
	}

	/**
	 * This method returns a tuple where the first element is a query to
	 * update the second element that is a record.
	 * 
	 * @param string $table	table's name
	 * @param array	$record	record to be updated
	 * 
	 * @return array|false tuple on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		protected
	 * @see			https://www.php.net/manual/en/function.empty
	 * @see			https://www.php.net/manual/en/function.array-key-exists
	 * @see			https://www.php.net/manual/en/function.unset
	 * @see			https://www.php.net/manual/en/function.array-keys
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
	protected function cleanUpdate(string $table, array $record): array|false
	{
		if (!$this->mapTable($table) || !$this->areTypesValid($record))
			return false;

		$record = self::array_key_diff($record, self::$TABLES[$table]['columns']);
		// columns that aren't part of the table must be deleted

		$primaryKey = self::$TABLES[$table]['primary_key'];
		if (empty($primaryKey) || !array_key_exists($primaryKey, $record))
			return false;
		// unable to update a record that doesn't have a primary key

		$primaryKeyValue = $record[$primaryKey];
		unset($record[$primaryKey]);
		// primary key cannot be changed

		$query = DatabaseStandard::generateUpdateStandard($table,
			$primaryKey, array_keys($record));

		$record[$primaryKey] = $primaryKeyValue;
		// the primary key must be part of the record

		return [
			$query,
			$record
		];
	}

	/**
	 * This method returns a tuple where the first element is a query to
	 * delete the second element that is a record.
	 * 
	 * @param string $table	table's name
	 * @param array	$record	record to be deleted
	 * 
	 * @return array|false tuple on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		protected
	 * @see			https://www.php.net/manual/en/function.empty
	 * @see			https://www.php.net/manual/en/function.array-key-exists
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
	protected function cleanDelete(string $table, array $record): array|false
	{
		if (!$this->mapTable($table) || !$this->areTypesValid($record))
			return false;

		$primaryKey = self::$TABLES[$table]['primary_key'];
		if (empty($primaryKey) || !array_key_exists($primaryKey, $record))
			return false;
		// unable to delete a record that doesn't have a primary key

		$query = DatabaseStandard::generateDeleteStandard($table, $primaryKey);

		return [
			$query,
			[$primaryKey => $record[$primaryKey]]
		];
	}

	/**
	 * This method returns an array with the values of $dict where the keys
	 * are elements of one of the arrays passed as argument in $lists.
	 * 
	 * @param		array	$dict	array of type key-value - or dictionary
	 * @param		array	$lists	variable length argument list
	 * 
	 * @return		array	@example array('fooID' => 'foo', 'fooBAR' => 'bar')
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.2
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

	/**
	 * This method returns a boolean value indicating whether the values
	 * inside of the record are compatible with database data types.
	 * 
	 * @return bool true on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.2
	 * @access		private
	 * @see			https://www.php.net/manual/en/function.gettype.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
	private function areTypesValid(array $record): bool
	{
		foreach ($record as $value) {
			$valueType = gettype($value);

			switch ($valueType) {
				case 'array':
				case 'object':
				case 'resource':
				case 'resource (closed)':
				case 'unknown type':
					return false;
			}
		}

		return true;
	}

	/**
	 * This method is responsible for retrieving a list of column names for a
	 * specified database table.
	 * 
	 * @return array|false array de columns on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		private
	 * @see			https://www.php.net/manual/en/pdostatement.fetchall
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
	private function getColumns(string $table): array|false
	{
		$query	= DatabaseStandard::generateColumns('tb', 'column_name');
		$stmt	= $this->query($query, array('tb' => $table));

		if ($stmt === false) return false;
		// there was an error

		$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		$response = [];
		foreach ($result as $value) {
			$response[] = $value['column_name'];
		}

		return $response;
	}

	/**
	 * This method is responsible for retrieving a list of column names that
	 * are marked as required (not nullable) for a specified database table.
	 * 
	 * @return array|false array of columns on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		private
	 * @see			https://www.php.net/manual/en/pdostatement.fetchall
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
	private function getRequiredColumns(string $table): array|false
	{
		$query = DatabaseStandard::generateRequiredColumns('tb');

		$stmt = $this->query($query, array('tb' => $table));
		if ($stmt === false) return false;
		// there was an error

		$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		$response = [];
		foreach ($result as $value) {
			$response[] = $value['COLUMN_NAME'];
		}

		return $response;
	}

	/**
	 * This method is responsible for retrieving a list of unique identifiers
	 * (likely column names) for a specified database table.
	 * 
	 * @return array|false array of columns on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		private
	 * @see			https://www.php.net/manual/en/pdostatement.fetchall
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
	private function getUniqueIdentifiers(string $table): array|false
	{
		$query = DatabaseStandard::generateUniqueIdentifiers('tb');

		$stmt = $this->query($query, array('tb' => $table));
		if ($stmt === false) return false;
		// there was an error

		$result	= $stmt->fetchAll(\PDO::FETCH_ASSOC);

		$response = [];
		foreach ($result as $value) {
			$response[] = $value['COLUMN_NAME'];
		}

		return $response;
	}

	/**
	 * This method is responsible for retrieving the primary key information
	 * for a specified database table.
	 * 
	 * @return array|false array with the name of the primary key and an indication if it's required on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		private
	 * @see			https://www.php.net/manual/en/pdostatement.fetch
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
	private function getPrimaryKeyName(string $table): array|false
	{
		$query = DatabaseStandard::generatePrimaryKeyStandard('tb',
			'key_name', 'is_key_required');
		$stmt = $this->query($query, array('tb' => $table));

		return $stmt === false ? false : $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	/**
	 * This method is responsible for mapping the structure and metadata of
	 * a database table into an internal data structure.
	 * 
	 * @return bool true on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		private
	 * @see			https://www.php.net/manual/en/function.array-key-exists.php
	 * @see			https://www.php.net/manual/en/function.empty
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
	private function mapTable(string $table): bool
	{
		if (array_key_exists($table, self::$TABLES))
			return true;

		$key = $this->getPrimaryKeyName($table);

		if ($key === false) return false;

		if (empty($key)) {
			$key['key_name'] = '';
			$key['is_key_required'] = false;
		}

		$unique = $this->getUniqueIdentifiers($table);

		if ($unique === false) return false;

		$requiredColumns = $this->getRequiredColumns($table);

		if ($requiredColumns === false) return false;

		$columns = $this->getColumns($table);

		if ($columns === false) return false;

		self::$TABLES[$table] = [
			'primary_key'			=>	$key['key_name'],
			'is_key_required'		=>	$key['is_key_required'] === 1,
			'unique_constraints'	=>	$unique,
			'required_columns'		=>	$requiredColumns,
			'columns'				=>	$columns
		];

		return true;
	}

	private function isPrimaryKeyRequired(string $table): bool
	{
		return self::$TABLES[$table]['is_key_required'];
	}
}
