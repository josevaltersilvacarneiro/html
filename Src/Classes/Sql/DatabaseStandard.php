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

namespace Josevaltersilvacarneiro\Html\Src\Classes\Sql;

/**
 * DatabaseStandard is a class for generating dynamic SQL queries
 * in PHP. It supports CREATE, READ, UPDATE and DELETE statements,
 * with method chaining for easy query construction. Parameter
 * binding is built-in for security, and it works across different
 * database systems.
 *
 * @method string generatePrimaryKeysStandard(string $table)
 * @method string generatePrimaryKeysIncremented(string $table)
 * @method string generateUniqueIdentifiers(string $table)
 * @method string generateRequiredColumns(string $table)
 * 
 * @method string generateCreateStandard(string $table, array $requiredColumns)
 * @method string generateReadStandard(string   $table, array $uniqueIdentifiers)
 * @method string generateUpdateStandard(string $table, array $uniqueIdentifiers, array $columns)
 * @method string generateDeleteStandard(string $table, array $uniqueIdentifiers)
 *
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.3
 * @see			https://en.wikipedia.org/wiki/SQL
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */

class DatabaseStandard
{
    /**
	 * This method generates the conditional
	 * of a query.
	 * 
     * @param       array   $uniqueIdentifiers list of columns with UNIQUE Constraint
	 * @return		string  conditional of a query @example "id = :id AND foo = :foo"
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		private
	 * @see			https://www.php.net/manual/en/function.implode.php
	 * @see			https://www.php.net/manual/en/function.preg-replace.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

    public static function generateCondition(array $uniqueIdentifiers): string
    {
        $codition = implode(' AND ',
            preg_replace("/^(.*)$/", "\\1 = :\\1", $uniqueIdentifiers));
        
        return $codition;
    }

	public static function generatePrimaryKeysStandard(string $table): string
	{
		$query = <<<QUERY
			SELECT	COLUMN_NAME
			FROM	INFORMATION_SCHEMA.COLUMNS
			WHERE	TABLE_NAME	= :$table
			AND		COLUMN_KEY	= 'PRI';"
		QUERY;

		return $query;
	}

	public static function generatePrimaryKeysIncremented(string $table): string
	{
		$query = <<<QUERY
			SELECT	COLUMN_NAME
			FROM	INFORMATION_SCHEMA.COLUMNS
			WHERE	TABLE_NAME	= :$table
			AND		COLUMN_KEY	= 'PRI'
			AND		EXTRA		= 'AUTO_INCREMENT';
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
				OR	COLUMN_KEY	= 'UNI');"
		QUERY;

		return $query;
	}

	public static function generateRequiredColumns(string $table): string
	{
		$query = <<<QUERY
			SELECT	COLUMN_NAME 
			FROM	INFORMATION_SCHEMA.COLUMNS 
			WHERE	TABLE_NAME = :$table
			AND		IS_NULLABLE = 'NO'
			AND		COLUMN_DEFAULT IS NULL
			AND		EXTRA != 'AUTO_INCREMENT';
		QUERY;

		return $query;
	}

    /**
	 * This method generates an SQL query for inserting a new
	 * record into a table. By providing the table name and
	 * an array with the names of the columns that will be
	 * inserted. It constructs an INSERT statement with the
	 * appropriate placeholders for parameter binding. It
	 * efficiently handles the query generation, ensuring proper
	 * syntax and data integrity. Once the query is generated,
	 * you can execute it separately using a database connection.
	 * 
     * @param       string  $table where the data will be stored
     * @param       array   $requiredColumns list of columns NOT NULL without DEFAULT
     * 
	 * @return		string  @example "INSERT INTO tbFOO (fooID, fooBAR) VALUES (:fooID, :fooBAR);"
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.2
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.implode.php
	 * @see			https://www.php.net/manual/en/function.preg-replace.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

    public static function generateCreateStandard(
        string $table, array $requiredColumns): string
    {
        $columns    = implode(', ', $requiredColumns);

        $values     = implode(', ',
            preg_replace("/^(.*)$/", ":\\1", $requiredColumns));
                
		$query = "INSERT INTO `$table` ($columns) VALUES ($values);";
 
		return $query;
    }

    /**
	 * This method generates an SQL query for retrieving data
	 * from a table. By specifying the table name and the array
	 * containing the unique identifiers of the table, it
	 * constructs a SELECT statement with the desired parameters,
	 * simplifying the process of creating a select query by
	 * handling the query construction, including column selection,
	 * table specification, and read condition. Once the query
	 * is generated, it can be executed separately using a database
	 * connection.
	 * 
     * @param       string  $table from where the data will be read
     * @param       array   $uniqueIdentifiers list of columns with UNIQUE Constraint
     * 
	 * @return		string  @example "SELECT * FROM tbFOO WHERE fooID = :fooID LIMIT 1;"
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.2
	 * @access		public
	 * @see			DatabaseStandard::generateCondition(array $uniqueIdentifiers): string
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

    public static function generateReadStandard(
        string $table, array $uniqueIdentifiers): string
    {
        $codition = self::generateCondition(
            uniqueIdentifiers: $uniqueIdentifiers);

		$query	= "SELECT * FROM `$table` WHERE $codition LIMIT 1;";

		return $query;
    }

    /**
	 * This method generates an SQL query for updating
	 * an existing record in a table. By providing the
	 * table name, unique identifiers and columns that
	 * will be updated, it constructs an UPDATE statement
	 * with the appropriate placeholders for parameter binding.
	 * It simplifies the process of creating an update query
	 * by handling the query construction, ensuring the proper
	 * syntax, and allowing for conditional updates. Once the
	 * query is generated, it can be executed separately using
	 * a database connection.
	 * 
     * @param       string  $table where the data will be updated
     * @param       array   $uniqueIdentifiers list of columns with UNIQUE Constraint
     * @param       array   $columns list of columns NOT NULL without DEFAULT
     * 
	 * @return		string  @example "UPDATE tbFOO SET fooBAR = :fooBAR WHERE fooID = :fooID LIMIT 1;"
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.2
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.implode.php
	 * @see			https://www.php.net/manual/en/function.preg-replace.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

    public static function generateUpdateStandard(
        string $table, array $uniqueIdentifiers, array $columns): string
    {
        $codition   = self::generateCondition(
            uniqueIdentifiers: $uniqueIdentifiers);

        $set        = implode(', ',
            preg_replace("/^(.*)$/", "\\1 = :\\1", $columns));

		$query	= "UPDATE `$table` SET $set WHERE $codition LIMIT 1;";

		return $query;
    }

    /**
	 * This method generates an SQL query for deleting a record
	 * from a table. By specifying the table name and an array
	 * of unique identifiers it constructs a DELETE statement with
	 * the necessary parameters. It simplifies the process of
	 * creating a delete query by handling the query construction,
	 * including table specification and deletion condition. Once
	 * the query is generated, it can be executed separately using
	 * a database connection.
	 * 
     * @param       string  $table where is the data that will be deleted
     * @param       array   $uniqueIdentifiers list of columns with UNIQUE Constraint
     * 
	 * @return		string  @example "DELETE FROM tbFOO WHERE fooID = :fooID LIMIT 1;"
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.2
	 * @access		public
	 * @see			DatabaseStandard::generateCondition(array $uniqueIdentifiers): string
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

    public static function generateDeleteStandard(
        string $table, array $uniqueIdentifiers): string
    {
        $codition = self::generateCondition(
            uniqueIdentifiers: $uniqueIdentifiers);
            
		$query	= "DELETE FROM `$table` WHERE $codition LIMIT 1;";

		return $query;
    }
}
