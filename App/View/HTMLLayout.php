<?php

/**
 * This package is responsible for displaying
 * the layout.
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
 * @package     Josevaltersilvacarneiro\Html\App\View
 */

header('Content-Type: text/html;charset=UTF-8');

/**
 * DOCUMENTATION
 * 
 * What is available for use and can be used:
 *
 * $this->title		- gets the page title
 * $this->description	- gets the page description, i.e. a brief overview
 * $this->keywords	- gets the page keywords
 * $this->robots	- gets the page robots, i.e. the indexing level for the page
 * $this->dir		- gets the page directory, i.e. the dir according to the served page (for example, Login/)
 *
 * $this->addHeader()	- adds the page header
 * $this->addMain()	- adds the main page content
 * $this->addFooter()	- adds the footer
 *
 * In addition, all global variables are available here.
 * @see Josevaltersilvacarneiro\Html\Settings\Settings
 * to find out which.
 *
 * @author	José V S Carneiro
 * @version	0.2
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license	GPLv3
 */

?>

<!DOCTYPE html>
<html lang='en'>
	<head>
		<meta charset="UTF-8">

		<title><?=$this->title?></title>

		<meta name=description content="<?=$this->description?>">
		<meta name=keywords content="<?=$this->keywords?>">
		<meta name=author content="<?=__AUTHOR__?>">
		<meta name=copyright content="Copyright (C) <?=date("Y") . ', ' . __AUTHOR__?>">
		<meta name=generator content="Vim">
		<meta name=rating content="14 years">
		<meta http-equiv=cache-control name=no-cache>
		<meta name=robots content="<?=$this->robots?>">
		
		<base href="<?=__URL__?>">
		
		<link rel=stylesheet href="<?=__CSS__ . 'Reset.css'?>">
		<link rel=stylesheet
			href="<?=__CSS__ . $this->dir . 'Style-' . __VERSION__ .'.css?id=' . time()?>">
		
		<noscript>Your browser doens't support Javascript.</noscript>
	</head>
	<body>
		<?php $this->addHeader() ?>
		<?php $this->addMain() ?>
		<?php $this->addFooter() ?>
	</body>
	<script type="module">

		/**
			* This calls the dispatcher, that will
			* run the appropriate controller.
		 */

		import { Dispatch }
			from "<?=__JS__ . 'App' . DIRECTORY_SEPARATOR . 'Dispatch.js'?>";
		
		let page = "<?=$this->dir?>".slice(0, -1).toLowerCase();	/* removes the slash and
											puts all characters
											in lower case	*/

		let dispatch = new Dispatch(page);
	</script>
</html>
