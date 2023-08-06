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

use Josevaltersilvacarneiro\Html\App\Model\Dao\GenericDao;

/**
 * The RequestDao class is a specialized DAO within the Dao package, dedicated
 * to managing user requests in the application. It leverages the generic
 * database operations from the GenericDao class and provides a well-organized
 * way to handle user request data in the database, making it easier to maintain
 * and interact with request entities throughout the application.
 *
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.1
 * @see			Josevaltersilvacarneiro\Html\App\Model\Dao\GenericDao
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */

final class RequestDao extends GenericDao
{
	public function __construct()
	{
		parent::__construct(_TB_REQUESTNAME);
	}
}