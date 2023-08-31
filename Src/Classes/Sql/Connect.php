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

use Josevaltersilvacarneiro\Html\Src\Interfaces\Sql\ConnectInterface;
use Josevaltersilvacarneiro\Html\Src\Classes\Exceptions\ConnectException;

/**
 * This class is responsible for creating connections with all databases.
 * 
 * @category  Connect
 * @package   Josevaltersilvacarneiro\Html\Src\Classes\Sql
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.5.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Classes/Sql
 */
class Connect implements ConnectInterface
{
    /**
     * Creates a mysql connection and returns it.
     * 
     * @return \PDO PDO instance
     * @throws ConnectException If the connection fails
     */
    public static function newMysqlConnection(): \PDO
    {
        try {
            return new \PDO(_DSN_MYSQL, _USER_MYSQL, _PASS_MYSQL);
        } catch (\PDOException $e) {
            $e = new ConnectException($e);
            $e->storeLog();
            throw $e;
        }
    }
}
