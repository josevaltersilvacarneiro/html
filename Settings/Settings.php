<?php

/**
 * This comprehensive PHP package is designed to simplify the
 * process of setting up and managing global variables. It
 * provides a convenient and organized approach to define,
 * access, and share variables across different components
 * of the application.
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
 * @package	Settings
 */

date_default_timezone_set("America/Bahia");		// sets up timezone

include_once 'DSN.php';     // variables responsible for accessing the database
include_once 'Dao.php';     // required tables and fields in the database

include_once 'Server.php';	// are available through the HTTP protocol
include_once 'Host.php';	// hosting machine
include_once 'MVC.php';		// var for the app
include_once 'Public.php';	// pub accessible

define('__VERSION__',	'v1');						// current code version
define('__AUTHOR__',	'José V S Carneiro');		// who wrote this code

define('__ACCESS__',	date(DATE_RSS, time()));	// date of access
