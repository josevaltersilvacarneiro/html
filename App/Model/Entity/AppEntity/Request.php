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

use Josevaltersilvacarneiro\Html\App\Model\Dao\RequestDao;
use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityDatabase;
use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityDateTime;

/**
 * The Request Entity represents a request. It contains properties and methods
 * to manage request-related data and operations.
 * 
 * @var ?int			$requestID		primary key
 * @var string			$requestIP		ip @example {192.168.1.56, ::1}
 * @var string			$requestPORT	port @example {5632}
 * @var EntityDateTime  $requestACCESS  date object of last access
 * 
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.1
 * @see			Josevaltersilvacarneiro\Html\App\Model\Entity\EntityDatabase
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */

#[RequestDao]
final class Request extends EntityDatabase
{
	# name of the property that stores the primary key
	public const IDNAME = 'requestID';

	/**
	 * This constructor is responsible for initializing a Request object
	 * with the provided values, while also performing various validation
     * checks.
	 * 
	 * The constructor initializes a Request object with the provided values,
	 * while also ensuring the validity of those values through validation
	 * checks.
	 * 
	 * If any of the validation checks fail, a \InvalidArgumentException is
     * thrown with a specific error message corresponding to the validation
     * failure.
	 * 
	 * @param ?int          $requestID
	 * @param string        $requestIP
	 * @param string        $requestPORT
	 * @param EntityDateTime $requestACCESS
	 * 
	 * @return void
	 * @throws \InvalidArgumentException
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.preg-match.php
	 * @see			https://www.php.net/manual/en/function.inet-pton.php
	 * @see			https://www.php.net/manual/en/class.invalidargumentexception.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function __construct(
		private ?int $requestID, private string $requestIP,
        private string $requestPORT,
		#[EntityDateTime] private EntityDateTime $requestACCESS
	)
	{
		if (inet_pton($requestIP) === false)
			throw new \InvalidArgumentException("${requestIP} isn't a valid IP", 1);

		if (!preg_match("/^[0-9]{1,5}$/", $requestPORT))
			throw new \InvalidArgumentException("${requestPORT} isn't a valid port", 1);

		if ($requestACCESS > new EntityDateTime())
			throw new \InvalidArgumentException($requestACCESS->getDatabaseRepresentation() .
				" is in the future", 1);
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
	 * This method is responsible for setting the requestIP property with
	 * the provided IP address, while also validating its format.
	 * 
	 * It ensures that the provided IP address value adheres to either the
	 * IPv4 or IPv6 format by performing regular expression pattern matches.
	 * This validation helps maintain data integrity and ensures that only
	 * valid IP addresses are assigned to the requestIP property.
	 * 
	 * If the provided IP address doesn't match either of the regular
	 * expression patterns, an \InvalidArgumentException is thrown with a
	 * custom error message indicating that the IP address isn't valid.
	 * 
	 * @param string $requestIP A human readable IPv4 or IPv6 address
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

	public function setRequestip(string $requestIP): void
	{
		if (inet_pton($requestIP) === false)
			throw new \InvalidArgumentException("${requestIP} isn't a IP valid", 1);

		$this->requestIP = $requestIP;
	}

	/**
	 * This method is responsible for setting the requestPORT property
	 * with the provided port number, while also validating its format.
	 * 
	 * It ensures that the provided port number value meets the specified
	 * format requirements. It validates the length of the port number
	 * and checks if it consists only of digits. This validation helps
	 * maintain data integrity and ensures that only valid port numbers
	 * are assigned to the requestPORT property.
	 * 
	 * If the provided port number doesn't meet both validation conditions,
	 * an \InvalidArgumentException is thrown with a custom error message
	 * indicating that the port number isn't valid.
	 * 
	 * @param string $requestPORT
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

	public function setRequestport(string $requestPORT): void
	{
		if (!preg_match("/^[0-9]{1,5}$/", $requestPORT))
			throw new \InvalidArgumentException("${requestPORT} isn't a valid port", 1);
		
		$this->requestPORT = $requestPORT;
	}

	/**
	 * This method is responsible for setting the requestACCESS property
	 * with the provided DateTimeImmutable object, while also
	 * validating its value.
	 * 
	 * It ensures that the provided request date value is valid by
	 * comparing it with the current request date. This validation
	 * helps maintain data integrity and ensures that only valid
	 * request dates are assigned to the requestACCESS property.
	 * 
	 * If the provided request date is less than the current request
	 * date, an \InvalidArgumentException is thrown with a custom error
	 * message indicating that the date isn't valid.
	 * 
	 * @param EntityDateTime $requestACCESS
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

	public function setRequestaccess(EntityDateTime $requestACCESS): void
	{
		if ($requestACCESS < $this->getRequestaccess())
			throw new \InvalidArgumentException($requestACCESS->getDatabaseRepresentation() .
				" isn't valid", 1);
		
		// if the provided request date is less than the
		// current request date, it indicates that the
		// date is invalid

		$this->requestACCESS = $requestACCESS;
	}

	public function getRequestid(): string
	{
		return $this->{$this->getIDNAME()};
	}

	public function getRequestip(): string
	{
		return $this->requestIP;
	}

	public function getRequestport(): string
	{
		return $this->requestPORT;
	}

	public function getRequestaccess(): EntityDateTime
	{
		return $this->requestACCESS;
	}

	/**
	 * This method checks if the request can be deleted based on LGPD regulations
     * by ensuring that the request is more than one year old. If the request is
     * less than one year old, the method prevents deletion and returns false.
     * Otherwise, it proceeds with the deletion operation and returns the result
     * of the deletion attempt.
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
        $todayDate  = new EntityDateTime();
        $interval   = \DateInterval::createFromDateString("1 year");

        if ($this->getRequestaccess()->add($interval) > $todayDate)
            return false;

        // if, when adding one year to the date of the request,
        // it's greater than today's date, then the request was
        // made less than one year ago. This means that it cannot
        // be deleted because of the LGPD

		return parent::killme();
	}
}
