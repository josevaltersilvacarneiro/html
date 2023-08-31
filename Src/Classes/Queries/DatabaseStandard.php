<?php

declare(strict_types=1);

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

namespace Josevaltersilvacarneiro\Html\Src\Classes\Queries;

/**
 * DatabaseStandard is a class for generating dynamic SQL queries in PHP.
 * It supports CREATE, READ, UPDATE and DELETE statements, with method
 * chaining for easy query construction. Parameter binding is built-in for
 * security, and it works across different database systems.
 * 
 * @method string generatePrimaryKeyStandard(string $table, string $columnAlias = 'COLUMN_NAME', string $requiredAlias = 'REQUIRED')
 * @method string generateUniqueIdentifiers(string $table)
 * @method string generateRequiredColumns(string $table)
 * @method string generateColumns(string $table, string $columnAlias = 'COLUMN_NAME')
 * 
 * @method string generateCreateStandard(string $table, array $requiredColumns)
 * @method string generateReadStandard(string   $table, array $uniqueIdentifiers)
 * @method string generateUpdateStandard(string $table, array $uniqueIdentifiers, array $columns)
 * @method string generateDeleteStandard(string $table, array $uniqueIdentifiers)
 * 
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.5
 * @see			https://en.wikipedia.org/wiki/SQL
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */
class DatabaseStandard
{
	public static function generatePrimaryKeyStandard(string $table,
		string $columnAlias = 'COLUMN_NAME', string $requiredAlias = 'REQUIRED'): string
	{
		$query = <<<QUERY
			SELECT	EXTRA != 'AUTO_INCREMENT' AS $requiredAlias,
				COLUMN_NAME AS $columnAlias
			FROM	INFORMATION_SCHEMA.COLUMNS
			WHERE	TABLE_NAME	= :$table
			AND		COLUMN_KEY	= 'PRI';
		QUERY;

		return $query;
	}

	public static function generateUniqueIdentifiers(string $table): string
	{
		$query = <<<QUERY
			SELECT	COLUMN_NAME
			FROM	INFORMATION_SCHEMA.COLUMNS
			WHERE	TABLE_NAME	= :$table
			AND		(COLUMN_KEY	= 'PRI'
				OR	COLUMN_KEY	= 'UNI');
		QUERY;

		return $query;
	}

	public static function generateRequiredColumns(string $table): string
	{
		$query = <<<QUERY
			SELECT	COLUMN_NAME 
			FROM	INFORMATION_SCHEMA.COLUMNS 
			WHERE	TABLE_NAME	= :$table
			AND		IS_NULLABLE	= 'NO'
			AND		COLUMN_DEFAULT IS NULL
			AND		EXTRA != 'AUTO_INCREMENT';
		QUERY;

		return $query;
	}

	public static function generateColumns(string $table,
		string $columnAlias = 'COLUMN_NAME'): string
	{
		$query = <<<QUERY
			SELECT	COLUMN_NAME AS $columnAlias
			FROM	INFORMATION_SCHEMA.COLUMNS 
			WHERE	TABLE_NAME = :$table;
		QUERY;

		return $query;
	}

    /**
	 * This method generates an SQL query for inserting a new record.
	 * 
     * @param string $table table's name
     * @param array $requiredColumns list of columns NOT NULL without DEFAULT
     * 
	 * @return string @example "INSERT INTO tbFOO (fooID, fooBAR) VALUES (:fooID, :fooBAR);"
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.3
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.implode.php
	 * @see			https://www.php.net/manual/en/function.preg-replace.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
    public static function generateCreateStandard(string $table,
		array $requiredColumns): string
    {
        $columns = implode(', ', $requiredColumns);
        $values = implode(', ',
            preg_replace("/^(.*)$/", ":\\1", $requiredColumns));

		$query = <<<QUERY
			INSERT INTO `$table`
			($columns) VALUES ($values);
		QUERY;

		return $query;
    }

    /**
	 * This method generates an SQL query for retrieving data from a table.
	 * 
     * @param string $table table's name
     * @param string $uniqueConstraint primary key or unique constraint
     * 
	 * @return string @example "SELECT * FROM tbFOO WHERE fooID = :fooID LIMIT 1;"
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.3
	 * @access		public
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
    public static function generateReadStandard(string $table,
		string $uniqueConstraint): string
    {
		$query	= <<<QUERY
			SELECT * FROM `$table`
			WHERE $uniqueConstraint = :$uniqueConstraint
			LIMIT 1;
		QUERY;

		return $query;
    }

    /**
	 * This method generates an SQL query for updating an existing record.
	 * 
     * @param string $table table's name
     * @param string $primaryKey
     * @param array $columns list of columns to be updated
     * 
	 * @return string @example "UPDATE tbFOO SET fooBAR = :fooBAR WHERE fooID = :fooID LIMIT 1;"
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.3
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.implode.php
	 * @see			https://www.php.net/manual/en/function.preg-replace.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
    public static function generateUpdateStandard(string $table,
		string $primaryKey, array $columns): string
    {
        $set = implode(', ', preg_replace("/^(.*)$/", "\\1 = :\\1", $columns));

		$query = <<<QUERY
			UPDATE	`$table`
			SET		$set
			WHERE	$primaryKey = :$primaryKey
			LIMIT 1;
		QUERY;

		return $query;
    }

    /**
	 * This method generates an SQL query for deleting a record.
	 * 
     * @param string $table table's name
     * @param string $primaryKey
     * 
	 * @return string @example "DELETE FROM tbFOO WHERE fooID = :fooID LIMIT 1;"
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.3
	 * @access		public
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
    public static function generateDeleteStandard(string $table,
		string $primaryKey): string
    {
		$query = <<<QUERY
			DELETE FROM `$table`
			WHERE $primaryKey = :$primaryKey
			LIMIT 1;
		QUERY;

		return $query;
    }
}
