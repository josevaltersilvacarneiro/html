<?php

declare(strict_types=1);

/**
 * This comprehensive PHP package is designed to simplify the process of setting up
 * and managing global variables. It provides a convenient and organized approach
 * to define, access and share variables across different components of the
 * application.
 * PHP VERSION 8.2.0
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
 * @category Settings
 * @package  Josevaltersilvacarneiro\Html\Settings
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  https://www.gnu.org/licenses/quick-guide-gplv3.html GPLv3
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Settings
 */

/*-------------------------------------*/
/* database credentials must be stored */
/* as environment variables            */
/*-------------------------------------*/

define('_DSN_MYSQL',  getenv('DSN_MYSQL'));
define('_USER_MYSQL', getenv('USER_MYSQL'));
define('_PASS_MYSQL', getenv('PASS_MYSQL'));
