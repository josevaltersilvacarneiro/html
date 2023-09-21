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
 * @category Dao
 * @package  Josevaltersilvacarneiro\Html\Src\Interfaces\Dao
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Dao
 */

namespace Josevaltersilvacarneiro\Html\Src\Interfaces\Dao;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Sql\SqlInterface;

/**
 * This interface represents a Dao.
 * 
 * @category  DaoInterface
 * @package   Josevaltersilvacarneiro\Html\Src\Interfaces\Dao
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.3
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Dao
 */
interface DaoInterface
{
    /**
     * This method persists the record in the database.
     * 
     * Note that it's your responsibility to ensure that the fields
     * in the $data array are the same as the fields in the database.
     * 
     * @param array $data key/value pair @example array('foo' => 'foobar')
     * 
     * @return bool true if the record was successfully persisted, false otherwise.
     */
    public function c(array $data): bool;

    /**
     * This method retrieves the record from the database based on
     * $uid.
     * 
     * @param array $data key/value pair where a key is a unique constraint field
     * 
     * @return array|false key/value pair on success; false otherwise.
     */
    public function r(array $data): array|false;

    /**
     * This method updates the record in the database.
     * 
     * @param array $data key/value pair
     * 
     * @return bool true on success; false otherwise.
     */
    public function u(array $data): bool;

    /**
     * This method deletes the record from the database.
     * 
     * @param array $data key/value pair where a key is the primary key field
     * 
     * @return bool true on success; false otherwise.
     */
    public function d(array $data): bool;

    /**
     * This method inserts a new register and returns the primary key.
     * 
     * @param array $data key/value pair
     * 
     * @return string|false primary key on success; false otherwise
     */
    public function ic(array $data): string|false;
}
