<?php

declare(strict_types=1);

/**
 * This is a comprehensive PHP package designed to streamline
 * the development of controllers in the application following
 * the MVC (Model-View-Controller) architectural pattern. It
 * provides a set of powerful tools and utilities to handle
 * user input, orchestrate application logic, and facilitate
 * seamless communication between the Model and View components.
 * 
 * Routing and Request Handling: This package offers a robust
 * routing system to map incoming requests to specific controller
 * actions. It allows you to define routes based on URL patterns
 * and HTTP methods, enabling clean and intuitive URL structures.
 * The package handles request parsing, parameter extraction, and
 * route matching, ensuring that the appropriate controller action
 * is invoked for each request.
 * 
 * Controller Actions and Middleware: The package provides a flexible
 * mechanism to define controller actions, which encapsulate the
 * application logic corresponding to specific user interactions or
 * request types. You can easily define and organize multiple actions
 * within a controller class, each responsible for processing a specific
 * user request. Additionally, it supports middleware functionality to
 * intercept and process requests before they reach the controller
 * actions, enabling cross-cutting concerns such as authentication,
 * authorization, and input validation.
 * 
 * Data Manipulation and Transformation: it facilitates data manipulation
 * and transformation tasks, allowing you to retrieve, modify, and
 * validate input data before passing it to the Model layer. The package
 * includes convenient traits to sanitize and validate user input, map
 * request parameters to appropriate data structures, and enforce data
 * integrity rules.
 * 
 * View Rendering and Response Generation: The package integrates with the
 * View layer to facilitate the rendering and generation of response content.
 * It provides utilities to pass data from the Model to the View, allowing
 * the View to display the relevant information to the user. The package
 * supports various response types, including HTML, JSON, XML, and others,
 * enabling you to deliver appropriate responses based on the client's needs.
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
 * @package     Josevaltersilvacarneiro\Html\App\Controller
 */

namespace Josevaltersilvacarneiro\Html\App\Controller;

use Josevaltersilvacarneiro\Html\App\Controller\Controller;
use Josevaltersilvacarneiro\Html\Src\Classes\Render\HTMLRender;

use Josevaltersilvacarneiro\Html\App\Model\Entity\Session;
use Josevaltersilvacarneiro\Html\App\Model\Service\SessionService;

/**
 * The HTMLController class is an implementation of Controller
 * interface, specifically designed for handling the rendering
 * of HTML pages in the application. It provides a cohesive set
 * of methods to handle page-specific logic, retrieve data from
 * the Model layer, and generate HTML responses for the user
 * interface.
 * 
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.1
 * @see			Josevaltersilvacarneiro\Html\App\Controller\Controller
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */

abstract class HTMLController extends HTMLRender implements Controller
{
	private Session $session;

	public function __construct()
	{
		$this->setSession(SessionService::startSession());
	}

	protected function setSession(Session $session): void
	{
		$this->session = $session;
	}

	public function getSession(): Session
	{
		return $this->session;
	}
}
