<?php

/**
 * This package stores the settings
 * and sets the globals variables.
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

date_default_timezone_set('America/Bahia');		// default timezone

define('__VERSION__',	'v1');				// current code version
define('__AUTHOR__',	'José V S Carneiro');		// who wrote this code

define('__IP__',	$_SERVER['REMOTE_ADDR']);	// the user's IP address
define('__PORT__',	$_SERVER['REMOTE_USER']);	// the user's port

define('__ACCESS__',	date(DATE_RSS, time()));	// date of access

define('__URL__',
	'http://' . $_SERVER['HTTP_HOST'] . '/'
);							// public url

define('__ROOT__',
	substr($_SERVER['DOCUMENT_ROOT'], -1) == DIRECTORY_SEPARATOR ?
	$_SERVER['DOCUMENT_ROOT'] :
	$_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR
);							// file path that will run

/*
 * MVC
 *
 * Model	- business rules
 * View		- the model's data is displayed
 * Controller	- interconnects inputs and outputs
 */

define('__CONTROLLER__',
	__ROOT__ . 'App' . DIRECTORY_SEPARATOR .
	'Controller' 	 . DIRECTORY_SEPARATOR
);

define('__VIEW__',
	__ROOT__ . 'App' . DIRECTORY_SEPARATOR .
	'View' 		 . DIRECTORY_SEPARATOR
);

/*
 * if you are going to use CDN, change the file path
 * for the file path in CDN
 */

define('__IMG__',	__URL__ . 'Public/Images/');	// image files
define('__CSS__',	__URL__ . 'Public/Css/');	// css files
define('__JS__',	__URL__ . 'Public/JavaScript');	// js files
