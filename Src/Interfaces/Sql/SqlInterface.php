<?php

declare(strict_types=1);

/**
 * Based on the project requirements, the interfaces will be written.
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

namespace Josevaltersilvacarneiro\Html\Src\Interfaces\Sql;

/**
 * This interface represents the Sql interface.
 * 
 * @category  SqlInterface
 * @package   Josevaltersilvacarneiro\Html\Src\Interfaces\Sql
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Sql
 */
interface SqlInterface
{
    /**
     * Initializes the connection.
     * 
     * @param \PDO $conn Connection
     */
    public function __construct(\PDO $conn);

    /**
     * This method returns the last inserted id.
     * 
     * @return string|false The last inserted id on success; false otherwise
     */
    public function getLastInsertId(): string|false;

    /**
     * This method performs a query.
     * 
     * @param string $query  The query to be performed
     * @param array  $params The parameters of the query
     * 
     * @return \PDOStatement|false statement on success; false otherwise
     */
    public function query(string $query, array $params = []): \PDOStatement|false;

    /**
     * This method performs a transaction.
     * 
     * Warning: It's not recommended to use select instructions
     * in a transaction.
     * 
     * @param array<array<string,array>> ...$queries Many queries in which each query
     *                                               has its parameters
     * 
     * @return \PDOStatement|false statement on success; false otherwise
     */
    public function queryAll(array ...$queries): \PDOStatement|false;
}
