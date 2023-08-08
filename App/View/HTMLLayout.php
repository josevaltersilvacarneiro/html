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
 * $this->title			- gets the page title
 * $this->description	- gets the page description, i.e. a brief overview
 * $this->keywords		- gets the page keywords
 * $this->robots		- gets the page robots, i.e. the indexing level for the page
 * $this->dir			- gets the page directory, i.e. the dir according to the served page (for example, Login/)
 *
 * $this->addHeader()	- adds the page header
 * $this->addMain()	- adds the main page content
 * $this->addFooter()	- adds the footer
 *
 * In addition, all global variables are available here.
 * @see Josevaltersilvacarneiro\Html\Settings\Settings
 * to find out which.
 *
 * @author		José V S Carneiro
 * @version		0.4
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
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
		<meta name=viewport content="width=device-width, initial-scale=1.0, maximum-scale=1">
		
		<base href="<?=__URL__?>">

		<!-- google web fonts -->

		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap">

		<!-- BEGIN bootstrap -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
		<!-- ENDOF bootstrap -->
		<link rel=stylesheet href="<?=__CSS__ . 'Reset.css'?>">
		<link rel=stylesheet href="<?=__CSS__ . 'Style.css?id=' . time()?>">
		<link rel=stylesheet
			href="<?=__CSS__ . $this->dir . 'Style-' . __VERSION__ .'.css?id=' . time()?>">
		
		<noscript>Your browser doens't support Javascript.</noscript>
	</head>
	<body>
		<?php $this->addHeader() ?>
		<?php $this->addMain() ?>
		<?php $this->addFooter() ?>
		<div id="liveAlertPlaceholder"></div>
	</body>
	<!-- BEGIN bootstrap -->
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
	<!-- ENDOF bootstrap -->
	<script type="module" src="<?=__JS__ . 'Public/app.js'?>"></script>
</html>
