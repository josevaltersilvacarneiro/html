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
 * @package     Josevaltersilvacarneiro\Html\App\Controller\Register
 */

namespace Josevaltersilvacarneiro\Html\App\Controller\Register;

use Josevaltersilvacarneiro\Html\App\Controller\HTMLController;

use Josevaltersilvacarneiro\Html\Src\Traits\{TraitIO,	TraitRedirect};

use Josevaltersilvacarneiro\Html\App\Model\Service\{SessionService, EmailService};

use Josevaltersilvacarneiro\Html\Src\Traits\{TraitValidateHash,
	TraitValidateName,	TraitValidateEmail,
	TraitValidateSalt,	TraitValidateCode};

/**
 * The register controller class is responsible for managing both
 * user registration and user removal functionalities within the
 * application. It facilitates the creation of new user accounts by
 * collecting and validating user-provided information. Additionally,
 * it handles the removal of existing user accounts.
 * 
 * @method	void	create()	creates a new user			- it's a route
 * @method	void	remove()	removes the logged user		- it's a route
 * 
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.1
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */
final class Register extends HTMLController
{
	use TraitRedirect;

	public const MYSELF = __URL__ . "register";

	public function __construct()
	{
		parent::__construct();

		$this->setDir("Register");
		$this->setTitle("Register");
		$this->setDescription("
			The register page offers a seamless and intuitive interface for
			users to create new accounts and join our platform. It presents
			fields for users to input their desired username, email address,
			password, and other necessary details. By clicking on the register
			button, users can complete the registration process and gain
			access to our system's features.
		");
		$this->setKeywords("MVC SOLID josevaltersilvacarneiro Register");
	}

	public function renderLayout(): void
	{
		if ($this->getSession()->isUserLogged())
			TraitRedirect::redirect(__URL__);

		// protecting logged-in users from viewing the page

		parent::renderLayout();
	}

	/**
	 * This method is responsible for handling the process of creating
	 * a new user account based on the provided registration data.
	 * It performs various validations and checks before creating the
	 * account and setting up a new session for the user.
	 * 
	 * Note: The create method ensures that the user is not already logged
	 * in before attempting to create a new user account. It validates the
	 * provided registration data, performs necessary checks, and creates a
	 * new user using the UserService. Session management is handled through
	 * the SessionService, allowing the user to immediately access the
	 * application after successful registration. Proper validation and
	 * security measures should be implemented to ensure the integrity and
	 * confidentiality of user information during the registration process.
	 * 
	 * @return	void
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.password-hash.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
	public function create(): void
	{
		if ($this->getSession()->isUserLogged())
			return ;

		// if the user already logged, couldn't create a new record

		$method	= 'POST';

		$name	= TraitIO::INPUT('name',	$method);
		$email	= TraitIO::INPUT('email',	$method);
		$hash	= TraitIO::INPUT('hash',	$method);
		$salt	= TraitIO::INPUT('salt',	$method);

		$code		= TraitIO::INPUT('code',		$method);
		$hashCode	= TraitIO::INPUT('hashCode',	$method);

		if (
			TraitValidateName::isNameValid(name:	$name)	&&
			TraitValidateEmail::isEmailValid(email:	$email)	&&
			TraitValidateHash::isHashValid(hash:	$hash)	&&
			TraitValidateSalt::isSaltValid(salt:	$salt)	&&

			TraitValidateCode::isHashCodeValid(hashCode:	$hashCode)	&&
			TraitValidateCode::isCodeValid(code:			$code)		&&

			EmailService::isEmailValid(email: $email, code: $code,
				hashCode: $hashCode)
		) {
			$newHash = password_hash($hash, PASSWORD_DEFAULT);

			if ($newHash === false) // could not find a secure hash
				return ;

			$user = new \Josevaltersilvacarneiro\Html\App\Model\Entity\AppEntity\User(
				userID:		null,
				userNAME:	$name,
				userEMAIL:	$email,
				userHASH:	$newHash,
				userSALT:	$salt,
				userON:		true
			);

			if (!$user->flush()) // if the user wasn't registered successfully
				return ;

			SessionService::restartSession($this->getSession(), user: $user);

			TraitRedirect::redirect(url: __URL__);
		}

		TraitRedirect::redirect(url: self::MYSELF);
	}

	/**
	 * This method is responsible for removing the user account associated
	 * with the currently logged-in session. It checks if the user is logged
	 * in before proceeding with the removal process. If the user is logged
	 * in, it calls the removeUser method from the UserService to perform the
	 * actual removal. After the removal is completed, the method redirects
	 * the user back to self::MYSELF.
	 * 
	 * Note: The remove method ensures that only logged-in users can initiate
	 * the removal process. It delegates the removal operation to the removeUser
	 * method in the UserService to handle the actual removal of the user
	 * account. Proper authorization and security measures should be in place to
	 * protect user data and prevent unauthorized removal of user accounts.
	 * 
	 * @return	void
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
	public function remove(): void
	{
		if (!$this->getSession()->isUserLogged())
			return ;

		// if the user isn't logged in, cannot be removed

		$this->getSession()->getUser()->killme();

		TraitRedirect::redirect(self::MYSELF);
	}
}
