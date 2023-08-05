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
 * Routing and Request Handling: it offers a robust
 * routing system to map incoming requests to specific controller
 * actions. It allows you to define routes based on URL patterns
 * and HTTP methods, enabling clean and intuitive URL structures.
 * The package handles request parsing, parameter extraction, and
 * route matching, ensuring that the appropriate controller action
 * is invoked for each request.
 * 
 * Controller Actions and Middleware: it provides a flexible
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
 * View Rendering and Response Generation: it integrates with the
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
 * @package     Josevaltersilvacarneiro\Html\App\Controller\Login
 */

namespace Josevaltersilvacarneiro\Html\App\Controller\Login;

use Josevaltersilvacarneiro\Html\App\Controller\HTMLController;

use Josevaltersilvacarneiro\Html\App\Model\Entity\AppEntity\User;
use Josevaltersilvacarneiro\Html\App\Model\Service\SessionService;

use Josevaltersilvacarneiro\Html\Src\Traits\{TraitIO,	TraitRedirect};
use Josevaltersilvacarneiro\Html\Src\Traits\TraitValidateEmail;
use Josevaltersilvacarneiro\Html\Src\Traits\TraitValidateHash;
use Josevaltersilvacarneiro\Html\Src\Traits\TraitValidateSalt;

/**
 * The Login Controller is responsible for handling both the login and
 * logout functionalities within the application. It receives the user's
 * login credentials, validates them against the stored user data or
 * authentication service, and manages the authentication process. Upon
 * successful login, the controller sets up the user session. Additionally,
 * it provides the functionality to log out the user, clearing their
 * session and redirecting them to an appropriate page. It ensures a smooth
 * and secure user authentication and logout experience.
 * 
 * @method	void	signin(?string $url)	logs the user in - it's a route
 * @method	void	signout()				logs the user out - it's a route
 * 
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.2
 * @see			Josevaltersilvacarneiro\Html\App\Controller\HTMLController
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */

final class Login extends HTMLController
{
	use TraitRedirect;

	public const MYSELF = __URL__ . "login";

	public function __construct()
	{
		parent::__construct();

		$this->setDir("Login");
		$this->setTitle("Login");
		$this->setDescription("
			The login page provides a simple and secure interface for users
			to authenticate and access the system. It offers a username and
			password input field for users to enter their credentials and a
			login button to initiate the authentication process.
		");
		$this->setKeywords("MVC SOLID josevaltersilvacarneiro login");
	}

	public function renderLayout(): void
	{
		if ($this->getSession()->isUserLogged())
			TraitRedirect::redirect(__URL__);

		// protecting logged-in users from viewing the page

		parent::renderLayout();
	}

	/**
	 * This method is responsible for handling the sign-in process for
	 * users within the application. It accepts an optional URL parameter
	 * to redirect the user to a specific page after successful sign-in.
	 * 
	 * Note: The signin method is responsible for authenticating user
	 * credentials, establishing the user session, and managing the sign-in
	 * process. It offers flexibility by allowing the specification of a URL
	 * for redirection after successful sign-in. If no URL is provided, it
	 * redirects the user to self::MYSELF page. Proper security measures,
	 * such as password hashing and prevention of unauthorized access, should
	 * be implemented to ensure a robust and secure sign-in process.
	 * 
	 * @param	?string $url where to go after login
	 * @return	void
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.4
	 * @access		public
	 * @see			https://www.php.net/manual/en/language.oop5.basic.php#language.oop5.basic.new
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
	
	public function signin(string $url = __URL__): void
	{
		if ($this->getSession()->isUserLogged())
			return ;

		// if the user already logged in, couldn't login

		$method		= 'POST';

		$email		= TraitIO::INPUT('email',	$method);
		$hash		= TraitIO::INPUT('hash',	$method);
		$newHash	= TraitIO::INPUT('newHash',	$method);
		$newSalt	= TraitIO::INPUT('newSalt',	$method);

		if (
			TraitValidateEmail::isEmailValid(email: $email)	&&
			TraitValidateHash::isHashValid(hash: $hash)		&&
			TraitValidateHash::isHashValid(hash: $newHash)	&&
			TraitValidateSalt::isSaltValid(salt: $newSalt))
		{
			// "THE PASSWORD CHANGES" every time the user
			// logs on to the platform

			$newHash	= password_hash($newHash, PASSWORD_DEFAULT);

			if ($newHash === false)
				return ; // could not find a secure hash
			
			$user		= User::newInstance(UID: $email);

			if (is_null($user))
				return ; // unregistered user - possible hacker attack

			if (!password_verify($hash, $user->getUserhash()))
				return ; // the password typed is wrong

			$this->getSession()->setUserSessionuser($user); // change the session user for
			$this->getSession()->flush(); // update in the database

			$this->getSession()->getUserSessionuser() // hash update
				->setPassword(userHASH: $newHash, userSALT: $newSalt);
			$this->getSession()->getUserSessionuser()->flush(); // update in the DB

			// if the hash hasn't been replaced by the new hash
			// the user will have no major security problems
			// and his hash will be replaced the next time he
			// logs in

			$url		= filter_var($url, FILTER_SANITIZE_URL);
			TraitRedirect::redirect(url: $url);
		}

		TraitRedirect::redirect(url: self::MYSELF);
	}

	/**
	 * This method is responsible for handling the sign-out process for
	 * users within the application. It checks if the user is logged in
	 * and proceeds to destroy the session, effectively logging out the
	 * user. After successful logout, the method redirects the user back
	 * to the login page: self::MYSELF.
	 * 
	 * @return	void
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @see			https://www.php.net/manual/en/language.oop5.basic.php#language.oop5.basic.new
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
	
	public function signout(): void
	{
		if (!$this->getSession()->isUserLogged())
			return ;

		// if the user isn't logged in, couldn't logout

		$userSession = $this->getSession();
		SessionService::destroySession(userSession: $userSession);

		TraitRedirect::redirect(url: self::MYSELF);
	}
}
