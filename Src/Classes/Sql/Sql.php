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
 * @package  Josevaltersilvacarneiro\Html\Src\Interfaces\Sql
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Sql
 */

namespace Josevaltersilvacarneiro\Html\Src\Classes\Sql;

/**
 * This class is responsible for executing SQL commands
 * in different databases.
 * 
 * @category  Sql
 * @package   Josevaltersilvacarneiro\Html\Src\Interfaces\Sql
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Sql
 */
abstract class Sql
{
    private const TYPES = [
        'integer' => \PDO::PARAM_INT,
        'boolean' => \PDO::PARAM_BOOL,
        'string'  => \PDO::PARAM_STR,
        'NULL'    => \PDO::PARAM_NULL,
    ];

    /**
     * Initializes the Sql.
     * 
     * @param \PDO $conn PDO instance
     */
    public function __construct(private readonly \PDO $conn)
    {
    }

    /**
     * This method returns the last inserted ID.
     * 
     * @return string|false The last inserted ID on success, false on failure
     */
    protected function getLastInsertedId(): string|false
    {
        return $this->conn->lastInsertId();
    }

    /**
     * This method sets the parameter in the statement.
     * 
     * @param \PDOStatement $statement PDOStatement instance
     * @param string        $key       parameter name
     * @param mixed         $value     parameter value
     * 
     * @return void
     */
    private function _setParam(
        \PDOStatement $statement,
        string $key,
        mixed $value
    ): void {
        $statement->bindParam(
            $key,
            $value,
            self::TYPES[gettype($value)]
        );
    }

    /**
     * This method sets the parameters in the statement.
     * 
     * @param \PDOStatement $statement  PDOStatement instance
     * @param array         $parameters parameters key/value pair
     * 
     * @return void
     */
    private function _setParams(
        \PDOStatement $statement,
        array $parameters = []
    ): void {
        foreach ($parameters as $key => $value) {
            $this->_setParam($statement, $key, $value);
        }
    }

    /**
     * This method executes the query and returns the PDOStatement instance.
     * 
     * @param string $query      SQL query
     * @param array  $parameters parameters key/value pair
     * 
     * @return \PDOStatement|false PDOStatement instance on success; false otherwise
     */
    public function query(string $query, array $parameters = []): \PDOStatement|false
    {
        try {
            $stmt = $this->conn->prepare($query);
            $this->_setParams($stmt, $parameters);
            $stmt->execute();
        } catch (\PDOException) {
            return false;
        }

        return $stmt;
    }

    /**
     * This method executes a transaction, that is represented for an array of
     * queries, which each value is an array of type list with the query and
     * its parameters.
     * 
     * @param array ...$queries queries list
     * 
     * @return \PDOStatement|false PDOStatement instance on success; false otherwise
     */
    public function queryAll(array ...$queries): \PDOStatement|false
    {
        try {
            if (!$this->conn->beginTransaction()) {
                return false;
            }

            foreach ($queries as $query) {
                list($sql, $parameters) = $query;

                $stmt = $this->conn->prepare($sql);
                $this->_setParams($stmt, $parameters);
                $stmt->execute();
            }

            $this->conn->commit();

            return $stmt;
        } catch (\PDOException) {
            $this->conn->rollBack();
            return false;
        }
    }
}
