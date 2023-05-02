<?php

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


define('__FILE_TYPE__', 'html'); 			// file type that will be returned

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
