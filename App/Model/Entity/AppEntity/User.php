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

use Josevaltersilvacarneiro\Html\App\Model\Dao\UserDao;
use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityDatabase;
use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityUserInterface;

/**
 * The User Entity represents a user. It encapsulates the user's
 * attributes and provides methods for setting and retrieving
 * the user's data.
 *
 * @var ?int		$userID		primary key
 * @var string		$userNAME	fullname @example José Carneiro
 * @var string		$userEMAIL	email @example git@josevaltersilvacarneiro.net
 * @var string		$userHASH	user's password hash
 * @var string		$userSALT	password salt
 * @var bool		$userON		true if the used is logged in; false otherwise
 * 
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.8
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */

#[UserDao]
class User extends EntityDatabase implements EntityUserInterface
{
	/**
	 * The constructor is responsible for initializing a User object with
	 * the provided values, while also performing validation checks.
	 * 
	 * Username Validation: checks if the length of the $userNAME exceeds 80
	 * characters or if it isn't a SHA256.
	 * 
	 * Email Validation: uses the FILTER_VALIDATE_EMAIL filter to check if
	 * the $userEMAIL is a valid email address.
	 * 
	 * Password Validation: checks if the length of the $userSALT is less
	 * than 8 characters or if the $userHASH isn't a SHA256.
	 * 
	 * If any of the validation checks fail, a \InvalidArgumentException is
	 * thrown with a specific error message corresponding to the validation
	 * failure.
	 * 
	 * @param ?int		$userID		primary key
	 * @param string	$userNAME	name of the user @example José Carneiro
	 * @param string	$userEMAIL	@example git@josevaltersilvacarneiro.net
	 * @param string	$userHASH	a SHA256 hash
	 * @param string	$userSALT	random letters used to generate the hash
	 * @param bool		$userON		true if the user is active; false otherwise
	 * 
	 * @return void
	 * @throws \InvalidArgumentException
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.4
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.mb-strlen.php
	 * @see			https://www.php.net/manual/en/function.preg-match.php
	 * @see			https://www.php.net/manual/en/function.filter-var.php
	 * @see			https://www.php.net/manual/en/filter.filters.validate.php
	 * @see			https://www.php.net/manual/en/function.password-get-info.php
	 * @see			https://www.php.net/manual/en/class.invalidargumentexception.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function __construct(
		private ?int $userID, private string $userNAME, private string $userEMAIL,
		private string $userHASH, private string $userSALT, private bool $userON
	)
	{
		if (mb_strlen($userNAME) > 80 || !preg_match("/^.{3,} .*.{3,}$/", $userNAME))
			throw new \InvalidArgumentException("${userNAME} isn't a valid name", 1);

		if (filter_var($userEMAIL, FILTER_VALIDATE_EMAIL) === false)
			throw new \InvalidArgumentException("${userEMAIL} isn't a valid email", 1);

		$passInfo = password_get_info($userHASH);

		if (mb_strlen($userSALT) < 8 || $passInfo["algoName"] === "unknown")
			throw new \InvalidArgumentException("This password isn't valid", 1);
	}

	public static function getIDNAME(): string
	{
		return 'userID';
	}

	public static function getUNIQUE(mixed $uID): string
	{
		$field = gettype($uID) === 'string' ? 'userEMAIL' : self::getIDNAME();

		return $field;
	}

	/**
	 * This method is responsible for setting the userNAME property with
	 * the provided name, while also validating its format.
	 * 
	 * Within the method, there are two validation conditions to ensure
	 * that the name is valid.
	 * 
	 * @param string $userNAME Name of the user @example José Carneiro
	 * 
	 * @return void
	 * @throws \InvalidArgumentException
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.3
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.mb-strlen.php
	 * @see			https://www.php.net/manual/en/function.preg-match.php
	 * @see			https://www.php.net/manual/en/function.mb-convert-case.php
	 * @see			https://www.php.net/manual/en/class.invalidargumentexception.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */
	public function setFullname(string $fullname): void
	{
		$errorMessage = <<<MESSAGE
			$fullname isn't a valid name
		MESSAGE;

		if (mb_strlen($fullname) > 80 || !preg_match("/^.{3,}. *.{3,}$/", $fullname))
			throw new \InvalidArgumentException($errorMessage, 1);

		$this->set($this->userNAME, mb_convert_case($fullname, MB_CASE_LOWER));
	}

	/**
	 * This method is responsible for setting the userEMAIL property with
	 * the provided email address, while also validating its format.
	 * 
	 * It ensures that the provided email address value is valid by using
	 * the FILTER_VALIDATE_EMAIL filter. This validation helps maintain
	 * data integrity and ensures that only valid email addresses are
	 * assigned to the userEMAIL property.
	 * 
	 * If the email address doesn't pass the validation, an \InvalidArgumentException
	 * is thrown with a custom error message indicating that the email
	 * address isn't valid.
	 * 
	 * @param string $userEMAIL Email @example git@josevaltersilvacarneiro.net
	 * 
	 * @return void
	 * @throws \InvalidArgumentException
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.2
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.filter-var.php
	 * @see			https://www.php.net/manual/en/filter.filters.validate.php
	 * @see			https://www.php.net/manual/en/class.invalidargumentexception.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function setEmail(string $email): void
	{
		$errorMessage = <<<MESSAGE
			$email isn't a valid email
		MESSAGE;

		if (filter_var($email, FILTER_VALIDATE_EMAIL) === false)
			throw new \InvalidArgumentException($errorMessage, 1);

		$this->set($this->userEMAIL, $email);
	}

	/**
	 * This method is responsible for setting the password-related
	 * properties (userHASH and userSALT) with the provided values,
	 * while also performing validation checks.
	 * 
	 * It validates the provided password-related values ($userHASH and
	 * $userSALT) and sets the corresponding properties. The validation
	 * checks ensure that the $userHASH and $userSALT meet the required
	 * length and that the $userHASH is a SHA256 hash. This helps
	 * maintain data integrity and ensures that only valid properties
	 * values of password are assigned to the userHASH and userSALT.
	 * 
	 * If the $userSALT length is less than 8, or if the $userHASH  isn't
	 * a SHA256 hash (using the regular expression pattern `/^[a-f0-9]{64}$/`,
	 * an \InvalidArgumentException is thrown with a custom error message
	 * indicating that the password isn't valid.
	 * 
	 * @param string $userHASH A SHA256 hash
	 * @param string $userSALT Random characters
	 * 
	 * @return void
	 * @throws \InvalidArgumentException
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.3
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.mb-strlen.php
	 * @see			https://www.php.net/manual/en/function.password-get-info.php
	 * @see			https://www.php.net/manual/en/class.invalidargumentexception.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function setPassword(string $hash, string $salt): void
	{
		$passInfo = password_get_info($hash);

		if (mb_strlen($salt) < 8 || $passInfo["algoName"] === "unknown")
			throw new \InvalidArgumentException("This password isn't valid", 1);

		$this->set($this->userHASH, $hash);
		$this->set($this->userSALT, $salt);
	}

	public function getFullname(): string
	{
		$username = explode(' ', $this->userNAME);

		foreach ($username as $key => $nm) {
			$case = mb_strlen($nm) > 2 ? MB_CASE_UPPER : MB_CASE_LOWER;
			$username[$key] = mb_convert_case($nm, $case);
		}

		return implode(' ', $username);
	}

	public function getEmail(): string
	{
		return $this->userEMAIL;
	}

	public function getHash(): string
	{
		return $this->userHASH;
	}

	public function getSalt(): string
	{
		return $this->userSALT;
	}

	public function isActive(): bool
	{
		return $this->userON;
	}

	/**
	 * This method is responsible for safely deactivating the current user by
	 * setting the $this->userON property to false. It ensures that any changes
	 * to the user entity are synchronized with the database before deactivation.
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
		$this->userON = false;

		if ($this->flush()) return true;

		$this->userON = true;

		// if the flush operation returns false, the method sets $this->userON
		// back to true, indicating that the user remains active, and returns false

		return false;
	}
}
