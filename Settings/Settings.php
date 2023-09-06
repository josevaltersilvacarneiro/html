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

define('_TIMEZONE_', 'America/Bahia');

date_default_timezone_set(_TIMEZONE_);

require_once 'Database.php'; // variables responsible for accessing the database
require_once 'Tables.php';   // required tables and fields in the database

require_once 'Host.php';     // hosting machine
require_once 'MVC.php';      // var for the app

define('__VERSION__',    'v1');                   // current code version
define('__AUTHOR__',    'José V S Carneiro');     // who wrote this code

define('__ACCESS__',    date(DATE_RSS, time())); // date of access
