<?php

/**
 * This function is responsible for importing
 * every file that contains the instantiated
 * class.
 *
 * @see		https://www.php.net/manual/en/function.spl-autoload-register.php
 * @author	José V S Carneiro
 * @version	0.1
 */

spl_autoload_register( function ($className)
{
	$path = __ROOT__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $className);

	if (file_exists($path . '.php'))
		require_once($path . '.php');
});
