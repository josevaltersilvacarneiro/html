<?php

declare(strict_types=1);

/**
 * The Entity package contains classes that represent the database
 * tables as entities. These entity classes encapsulate the structure
 * and behavior of specific tables, providing a convenient way to
 * interact with the corresponding database records.
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

namespace Josevaltersilvacarneiro\Html\App\Model\Entity;

use Josevaltersilvacarneiro\Html\App\Model\Entity\User;
use Josevaltersilvacarneiro\Html\App\Model\Dao\SessionDao;

/**
 * The Session Entity represents a session. It contains properties
 * and methods to manage session-related data and operations.
 *
 * @var string				$sessionID		primary key
 * @var ?User				$sessionUSER	foreign key
 * @var string				$sessionIP		ip @example {192.168.1.56, ::1}
 * @var string				$sessionPORT	port @example {5632}
 * @var \DateTimeImmutable	$sessionDATE	date object of last access
 * @var bool				$sessionON		true if session is valid; false otherwise
 *
 * @method bool isUserLogged()	true if the user is logged in; false otherwise
 * 
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.3
 * @see			Josevaltersilvacarneiro\Html\App\Model\Entity\Entity
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */

#[SessionDao()]
final class Session extends Entity
{
	# name of the property that stores the primary key
	public const IDNAME = 'sessionID';

	/**
	 * This constructor is responsible for initializing a Session object
	 * with the provided values, while also performing various
	 * validation checks.
	 * 
	 * The constructor initializes a Session object with the provided values,
	 * while also ensuring the validity of those values through validation
	 * checks.
	 * 
	 * If any of the validation checks fail, a \DomainException is thrown with
	 * a specific error message corresponding to the validation failure.
	 * 
	 * @param string	$sessionID
	 * @param ?User		$sessionUSER
	 * @param string	$sessionIP
	 * @param string	$sessionPORT
	 * @param \DateTimeImmutable $sessionDATE
	 * @param bool		$sessionON
	 * 
	 * @return void
	 * @throws \DomainException
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.preg-match.php
	 * @see			https://www.php.net/manual/en/function.is-null.php
	 * @see			https://www.php.net/manual/en/function.inet-pton.php
	 * @see			https://www.php.net/manual/en/class.domainexception.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function __construct(
		private string $sessionID, #[User] private ?User $sessionUSER,
		private string $sessionIP, private string $sessionPORT,
		#[\DateTimeImmutable] private \DateTimeImmutable $sessionDATE, private bool $sessionON
	)
	{
		if (!preg_match("/^([a-f0-9]{64})$/", $sessionID))
			throw new \DomainException("${sessionID} isn't a SHA256", 1);

		// \DomainException is thrown indicating that the
		// sessionID isn't a valid SHA256 value

		if (!(is_null($sessionUSER) || $sessionUSER->isUserActive()))
			throw new \DomainException($sessionUSER->getUserid() . " isn't active", 1);

		// If the user isn't active, a \DomainException is thrown
		// indicating that the user isn't active

		if (inet_pton($sessionIP) === false)
			throw new \DomainException("${sessionIP} isn't a valid IP", 1);

		if (!preg_match("/^[0-9]{1,5}$/", $sessionPORT))
			throw new \DomainException("${sessionPORT} isn't a valid port", 1);

		if ($sessionDATE > new \DateTimeImmutable())
			throw new \DomainException($sessionDATE->format("Y-m-d H:i:s") .
				" is in the future", 1);
	}

	public static function getIDNAME(): string
	{
		return self::IDNAME;
	}

	/**
	 * This method is responsible for setting the sessionUSER property
	 * with the provided User object, while also validating the user's
	 * activity status.
	 * 
	 * It ensures that the provided user object is either null or represents
	 * an active user by invoking the isUserActive method. This validation
	 * helps maintain data integrity and ensures that only valid user objects
	 * are assigned to the sessionUSER property.
	 * 
	 * It uses the isUserActive method on the User object to determine if
	 * the user is active. If the user isn't active, an \InvalidArgumentException
	 * is thrown with a custom error message indicating that the user isn't
	 * active.
	 * 
	 * @param ?User $sessionUSER
	 * 
	 * @return void
	 * @throws \InvalidArgumentException
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @see			https://www.php.net/manual/en/class.invalidargumentexception.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function setSessionuser(?User $sessionUSER): void
	{
		if (!(is_null($sessionUSER) || $sessionUSER->isUserActive()))
			throw new \InvalidArgumentException("User isn't active", 1);
		
		$this->sessionUSER = $sessionUSER;
	}

	/**
	 * This method is responsible for setting the sessionIP property with
	 * the provided IP address, while also validating its format.
	 * 
	 * It ensures that the provided IP address value adheres to either the
	 * IPv4 or IPv6 format by performing regular expression pattern matches.
	 * This validation helps maintain data integrity and ensures that only
	 * valid IP addresses are assigned to the sessionIP property.
	 * 
	 * If the provided IP address does not match either of the regular
	 * expression patterns, an \InvalidArgumentException is thrown with a
	 * custom error message indicating that the IP address is not valid.
	 * 
	 * @param string $sessionIP A human readable IPv4 or IPv6 address
	 * 
	 * @return void
	 * @throws \InvalidArgumentException
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.inet-pton.php
	 * @see			https://www.php.net/manual/en/class.invalidargumentexception.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function setSessionip(string $sessionIP): void
	{
		if (inet_pton($sessionIP) === false)
			throw new \InvalidArgumentException("${sessionIP} isn't a IP valid", 1);

		$this->sessionIP = $sessionIP;
	}

	/**
	 * This method is responsible for setting the sessionPORT property
	 * with the provided port number, while also validating its format. 
	 * 
	 * It ensures that the provided port number value meets the specified
	 * format requirements. It validates the length of the port number
	 * and checks if it consists only of digits. This validation helps
	 * maintain data integrity and ensures that only valid port numbers
	 * are assigned to the sessionPORT property.
	 * 
	 * If the provided port number does not meet both validation conditions,
	 * an \InvalidArgumentException is thrown with a custom error message
	 * indicating that the port number is not valid.
	 * 
	 * @param string $sessionPORT
	 * 
	 * @return void
	 * @throws \InvalidArgumentException
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.preg-match.php
	 * @see			https://www.php.net/manual/en/class.invalidargumentexception.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function setSessionport(string $sessionPORT): void
	{
		if (!preg_match("/^[0-9]{1,5}$/", $sessionPORT))
			throw new \InvalidArgumentException("${sessionPORT} isn't a valid port", 1);
		
		$this->sessionPORT = $sessionPORT;
	}

	/**
	 * This method is responsible for setting the sessionDATE property
	 * with the provided DateTimeImmutable object, while also
	 * validating its value.
	 * 
	 * It ensures that the provided session date value is valid by
	 * comparing it with the current session date. This validation
	 * helps maintain data integrity and ensures that only valid
	 * session dates are assigned to the sessionDATE property.
	 * 
	 * If the provided session date is less than the current session
	 * date, an \InvalidArgumentException is thrown with a custom error
	 * message indicating that the date isn't valid.
	 * 
	 * @param \DateTimeImmutable $sessionDATE
	 * 
	 * @return void
	 * @throws \InvalidArgumentException
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @see			https://www.php.net/manual/en/class.invalidargumentexception.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function setSessiondate(\DateTimeImmutable $sessionDATE): void
	{
		if ($sessionDATE < $this->getSessiondate())
			throw new \InvalidArgumentException($sessionDATE->format("Y-m-d H:i:s") .
				" isn't valid", 1);
		
		// If the provided session date is less than the
		// current session date, it indicates that the
		// date is invalid
		
		$this->sessionDATE = $sessionDATE;
	}

	/**
	 * The method is responsible for setting the session on property
	 * with the provided boolean value.
	 * 
	 * It updates the session on status property based on the provided
	 * boolean value and the existing value of the sessionON property.
	 * The logical AND operation ensures that the session on status is
	 * only set to true if both the existing value and the provided
	 * value are true.
	 * 
	 * Once destroyed, the session can never be activated.
	 * 
	 * @param bool $sessionON
	 * 
	 * @return void
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @see			https://en.wikipedia.org/wiki/Boolean_algebra
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function setSessionon(bool $sessionON): void
	{
		$this->sessionON = $this->$sessionON && $sessionON;
	}

	public function getSessionid(): string
	{
		return $this->{$this->getIDNAME()};
	}

	public function getSessionuser(): ?User
	{
		return $this->sessionUSER;
	}

	public function getSessionip(): string
	{
		return $this->sessionIP;
	}

	public function getSessionport(): string
	{
		return $this->sessionPORT;
	}

	public function getSessiondate(): \DateTimeImmutable
	{
		return $this->sessionDATE;
	}

	public function getSessionon(): bool
	{
		return $this->sessionON;
	}

	public function isUserLogged(): bool
	{
		return !is_null($this->getSessionuser());
	}
}
