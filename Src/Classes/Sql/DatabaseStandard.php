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
 * This class generates all sql querys.
 *
 * @method string generateCreateStandard(string $table, array $requiredColumns)
 * @method string generateReadStandard(string   $table, array $uniqueIdentifiers)
 * @method string generateUpdateStandard(string $table, array $uniqueIdentifiers, array $columns)
 * @method string generateDeleteStandard(string $table, array $uniqueIdentifiers)
 *
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.1
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

    /**
	 * This method generates the query to
	 * create a register and returns it.
	 * 
     * @param       string  $table where the data will be stored
     * @param       array   $requiredColumns list of columns NOT NULL without DEFAULT
     * 
	 * @return		string  @example "INSERT INTO tbFOO (fooID, fooBAR) VALUES (:fooID, :fooBAR);"
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
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
	 * This method generates the query to
	 * read a register and returns it.
	 * 
     * @param       string  $table from where the data will be read
     * @param       array   $uniqueIdentifiers list of columns with UNIQUE Constraint
     * 
	 * @return		string  @example "SELECT * FROM tbFOO WHERE fooID = :fooID LIMIT 1;"
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
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
	 * This method generates the query to
	 * update a register and returns it.
	 * 
     * @param       string  $table where the data will be updated
     * @param       array   $uniqueIdentifiers list of columns with UNIQUE Constraint
     * @param       array   $columns list of columns NOT NULL without DEFAULT
     * 
	 * @return		string  @example "UPDATE tbFOO SET fooBAR = :fooBAR WHERE fooID = :fooID LIMIT 1;"
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
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
	 * This method generates the query to
	 * delete a register and returns it.
	 * 
     * @param       string  $table where is the data that will be deleted
     * @param       array   $uniqueIdentifiers list of columns with UNIQUE Constraint
     * 
	 * @return		string  @example "DELETE FROM tbFOO WHERE fooID = :fooID LIMIT 1;"
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
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
