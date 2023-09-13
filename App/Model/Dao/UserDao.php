<?php

declare(strict_types=1);

/**
 * The Dao (Data Access Object) package is a crucial component of the application
 * responsible for handling the interaction between the application and the
 * database. It provides a set of classes that encapsulate database operations,
 * such as data retrieval, insertion, update, and deletion, for various entities
 * or tables in the system.
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
 * @package  Josevaltersilvacarneiro\Html\App\Model\Dao
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/App/Model/Dao
 */

namespace Josevaltersilvacarneiro\Html\App\Model\Dao;

use Josevaltersilvacarneiro\Html\Src\Classes\Dao\GenericDao;
use Josevaltersilvacarneiro\Html\Src\Classes\Sql\Connect;

/**
 * This class is responsible for handling user-related data in the
 * database. It provides methods to perform database operations related
 * to user entities, including creating new user records, retrieving
 * user information, updating user data, and deleting user records.
 * 
 * @category  UserDao
 * @package   Josevaltersilvacarneiro\Html\App\Model\Dao
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.2.0
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Model/Dao
 */
final class UserDao extends GenericDao
{
    /**
     * Initializes the UserDao object.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct(Connect::newMysqlConnection(), _TB_USERNAME);
    }
}
