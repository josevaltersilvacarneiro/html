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
use Josevaltersilvacarneiro\Html\App\Model\Dao\UserSessionDao;

use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityDateTime;
use Josevaltersilvacarneiro\Html\App\Model\Entity\EntitySessionInterface;
use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityUserInterface;
use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityRequestInterface;

use Josevaltersilvacarneiro\Html\App\Model\Entity\AppEntity\Request;
use Josevaltersilvacarneiro\Html\App\Model\Entity\AppEntity\User;

/**
 * The UserSession Entity represents a session. It contains properties
 * and methods to manage userSession-related data and operations.
 * 
 * @var string					$userSessionID		primary key
 * @var ?EntityUserInterface	$userSessionUSER	foreign key
 * @var EntityRequestInterface	$userSessionREQUEST	foreign key
 * 
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.8
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */

#[UserSessionDao]
final class UserSession extends EntityDatabase implements EntitySessionInterface
{
	private const numberOfDaysToExpire = 1;

	/**
	 * This constructor is responsible for initializing a UserSession object
	 * with the provided values, while also performing various validation
	 * checks.
	 * 
	 * If any of the validation checks fail, a \InvalidArgumentException is thrown
	 * with a specific error message corresponding to the validation failure.
	 * 
	 * @param string				$userSessionID
	 * @param ?EntityUserInterface	$userSessionUSER
	 * @param EntityRequestInterface $userSessionREQUEST
	 * 
	 * @return void
	 * @throws \InvalidArgumentException
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.3
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.preg-match.php
	 * @see			https://www.php.net/manual/en/class.invalidargumentexception.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function __construct(
		private string $userSessionID,
		#[User] private ?EntityUserInterface $userSessionUSER,
		#[Request] private EntityRequestInterface $userSessionREQUEST
	)
	{
		if (!preg_match("/^([a-f0-9]{64})$/", $userSessionID))
			throw new \InvalidArgumentException("${userSessionID} isn't a SHA256", 1);

		// \InvalidArgumentException is thrown indicating that the sessionID
		// isn't a valid SHA256 value

		if (!(is_null($userSessionUSER) || $userSessionUSER->isActive()))
			throw new \InvalidArgumentException($userSessionUSER->getID() . " isn't active", 1);

		// if the user isn't active, a \InvalidArgumentException is thrown
		// indicating that the user isn't active
	}

	public static function getIDNAME(): string
	{
		return 'userSessionID';
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
	 * It ensures that the provided user object represents an active user by
	 * invoking the isActive method. This validation helps maintain data
	 * integrity and ensures that only valid user objects are assigned to the
	 * userSessionUSER property.
	 * 
	 * @param EntityUserInterface $userSessionUSER
	 * 
	 * @return void
	 * @throws \InvalidArgumentException
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.4
	 * @access		public
	 * @see			https://www.php.net/manual/en/class.invalidargumentexception.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function setUser(EntityUserInterface $user): void
	{
		$errorMessage = <<<MESSAGE
			This session belongs to another user
			or ${$user->getFullname()} isn't active.
		MESSAGE;

		if ($this->isUserLogged() || !$user->isActive())
			throw new \InvalidArgumentException($errorMessage, 1);

		$this->set($this->userSessionUSER, $user);
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
	 * @version		0.3
	 * @access		public
	 * @see			https://www.php.net/manual/en/class.invalidargumentexception.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function setRequest(EntityRequestInterface $request): void
	{
		if ($this->getRequest()->getAccessDate() > $request->getAccessDate())
			throw new \InvalidArgumentException("The request is old", 1);

		$this->set($this->userSessionREQUEST, $request);
	}

	public function getUser(): ?EntityUserInterface
	{
		return $this->userSessionUSER;
	}

	public function getRequest(): EntityRequestInterface
	{
		return $this->userSessionREQUEST;
	}

	public function isExpired(): bool
	{
		return $this->getRequest()->getAccessDate()
			->diff(new EntityDateTime, true)->days > self::numberOfDaysToExpire;
	}

	public function isUserLogged(): bool
	{
		return !is_null($this->getUser());
	}
}
