<?php

declare(strict_types=1);

/**
 * This package is a sub-package within the Entity package and focuses on
 * handling entities that represent tables in the database.
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
 * @package     Josevaltersilvacarneiro\Html\App\Model\Entity
 */

namespace Josevaltersilvacarneiro\Html\App\Model\Entity\AppEntity;

use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityDatabase;
use Josevaltersilvacarneiro\Html\App\Model\Entity\AppEntity\{User, Request};
use Josevaltersilvacarneiro\Html\App\Model\Dao\UserSessionDao;

/**
 * The UserSession Entity represents a session. It contains properties
 * and methods to manage userSession-related data and operations.
 * 
 * @var string	$userSessionID		primary key
 * @var ?User	$userSessionUSER	foreign key
 * @var Request	$userSessionREQUEST	foreign key
 * @var bool	$userSessionON		true if session is active; false otherwise
 * 
 * @method bool isUserLogged()	true if the user is logged in; false otherwise
 * 
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.7
 * @see			Josevaltersilvacarneiro\Html\App\Model\Entity\Entity
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */

#[UserSessionDao]
final class UserSession extends EntityDatabase
{
	# name of the property that stores the primary key
	public const IDNAME = 'userSessionID';

	/**
	 * This constructor is responsible for initializing a UserSession object
	 * with the provided values, while also performing various validation
	 * checks.
	 * 
	 * If any of the validation checks fail, a \InvalidArgumentException is thrown
	 * with a specific error message corresponding to the validation failure.
	 * 
	 * @param string	$userSessionID
	 * @param ?User		$userSessionUSER
	 * @param Request	$userSessionREQUEST
	 * @param bool		$userSessionON
	 * 
	 * @return void
	 * @throws \InvalidArgumentException
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.2
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.preg-match.php
	 * @see			https://www.php.net/manual/en/function.is-null.php
	 * @see			https://www.php.net/manual/en/class.invalidargumentexception.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function __construct(
		private string $userSessionID, #[User] private ?User $userSessionUSER,
		#[Request] private Request $userSessionREQUEST, private bool $userSessionON
	)
	{
		if (!preg_match("/^([a-f0-9]{64})$/", $userSessionID))
			throw new \InvalidArgumentException("${userSessionID} isn't a SHA256", 1);

		// \InvalidArgumentException is thrown indicating that the sessionID
		// isn't a valid SHA256 value

		if (!(is_null($userSessionUSER) || $userSessionUSER->isUserActive()))
			throw new \InvalidArgumentException($userSessionUSER->getUserid() . " isn't active", 1);

		// if the user isn't active, a \InvalidArgumentException is thrown
		// indicating that the user isn't active
	}

	public static function getIDNAME(): string
	{
		return self::IDNAME;
	}

	public static function getUNIQUE(mixed $uID): string
	{
		return self::getIDNAME();
	}

	/**
	 * This method is responsible for setting the userSessionUSER property
	 * with the provided User object, while also validating the user's
	 * activity status.
	 * 
	 * It ensures that the provided user object is either null or represents
	 * an active user by invoking the isUserActive method. This validation
	 * helps maintain data integrity and ensures that only valid user objects
	 * are assigned to the userSessionUSER property.
	 * 
	 * It uses the isUserActive method on the User object to determine if the
	 * user is active. If the user isn't active, an \InvalidArgumentException
	 * is thrown with a custom error message indicating that the user isn't
	 * active.
	 * 
	 * @param ?User $userSessionUSER
	 * 
	 * @return void
	 * @throws \InvalidArgumentException
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.3
	 * @access		public
	 * @see			https://www.php.net/manual/en/class.invalidargumentexception.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function setUserSessionuser(?User $userSessionUSER): void
	{
		if (!(is_null($userSessionUSER) || $userSessionUSER->isUserActive()))
			throw new \InvalidArgumentException("User isn't active", 1);
		
		$this->set($this->userSessionUSER, $userSessionUSER);
	}

	/**
	 * This method is responsible for setting the userSessionREQUEST property
	 * with the provided Request object, while also validating if the request
	 * belonging to the UserSession is older than $userSessionREQUEST.
	 * 
	 * If the current Request isn't older than the provided Request, the method
	 * throws an \InvalidArgumentException with the message "The request is old"
	 * and a code 1, indicating that the assignment isn't allowed.
	 * 
	 * @param Request $userSessionREQUEST New Request
	 * 
	 * @return void
	 * @throws \InvalidArgumentException
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.2
	 * @access		public
	 * @see			https://www.php.net/manual/en/class.invalidargumentexception.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function setUserSessionrequest(Request $userSessionREQUEST): void
	{
		if ($this->getUserSessionrequest()->getRequestaccess() >
			$userSessionREQUEST->getRequestaccess())
			throw new \InvalidArgumentException("The request is old", 1);

		$this->set($this->userSessionREQUEST, $userSessionREQUEST);
	}

	/**
	 * The method is responsible for setting the userSessionON property with
	 * the provided boolean value.
	 * 
	 * It updates the userSessionON status property based on the provided
	 * boolean value and the existing value of the userSessionON property.
	 * The logical AND operation ensures that the userSessionON status is
	 * only set to true if both the existing value and the provided
	 * value are true.
	 * 
	 * Once destroyed, the userSession can never be activated.
	 * 
	 * @param bool $userSessionON
	 * 
	 * @return void
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.3
	 * @access		public
	 * @see			https://en.wikipedia.org/wiki/Boolean_algebra
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function setUserSessionon(bool $userSessionON): void
	{
		$this->set($this->userSessionON, $this->$userSessionON && $userSessionON);
	}

	public function getUserSessionid(): string
	{
		return $this->{$this->getIDNAME()};
	}

	public function getUserSessionuser(): ?User
	{
		return $this->userSessionUSER;
	}

	public function getUserSessionrequest(): Request
	{
		return $this->userSessionREQUEST;
	}

	public function getUserSessionon(): bool
	{
		return $this->userSessionON;
	}

	public function isUserLogged(): bool
	{
		return !is_null($this->getUserSessionuser());
	}

	/**
	 * This method is responsible for safely ending the current userSession
	 * and ensuring that the entity is synchronized with the database before
	 * terminating it.
	 * 
	 * @return bool true on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function killme(): bool
	{
		$this->userSessionON = false;

		if ($this->flush()) return true;

		$this->userSessionON = true;

		// the method sets $this->userSessionON back to true to keep the
		// userSession active and returns false, indicating that the userSession
		// termination process encountered an error or synchronization with the
		// database failed

		return false;
	}
}
