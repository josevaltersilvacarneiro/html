<?php

declare(strict_types=1);

/**
 * This package is responsible for providing functionalities
 * and utilities to access the database and control the entities
 * within the application. It serves as an intermediary layer
 * between the application's business logic and the underlying
 * database, offering services for data retrieval, manipulation,
 * and entity management.
 * 
 * It offers services for managing relationships between entities
 * in the database. It provides utilities for defining and enforcing
 * relationships such as one-to-one, one-to-many, or many-to-many
 * associations. It simplifies the handling of entity relationships
 * and supports efficient data retrieval across related entities.
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
 * @package     Josevaltersilvacarneiro\Html\App\Model\Service
 */

namespace Josevaltersilvacarneiro\Html\App\Model\Service;

use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityDateTime;
use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityRequestInterface;
use Josevaltersilvacarneiro\Html\App\Model\Entity\EntitySessionInterface;
use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityUserInterface;
use Josevaltersilvacarneiro\Html\App\Model\Service\Service;
use Josevaltersilvacarneiro\Html\Src\Classes\Log\ServiceLog;

/**
 * The SessionService class is responsible for providing services
 * to create, manage, and destroy sessions within the application.
 * It offers functionalities to handle session data, ensuring its
 * security by encrypting and decrypting the session information.
 * 
 * SessionService Creation and Destruction: This class facilitates
 * the creation of new sessions when a user initiates a session
 * with the application. It generates a unique $sessionID for each
 * user and initializes the session data. Additionally, it provides
 * methods to destroy sessions and remove session data when a user
 * logs out.
 * 
 * SessionService Data Management: This offers services for managing
 * session data throughout the user's interaction with your application.
 * It allows you to store and retrieve information specific to each
 * user's session, such as user preferences, authentication details, or
 * temporary data required during the session's lifespan.
 * 
 * SessionService Encryption: This class ensures the security and privacy
 * of session data by providing encryption services. It encrypts the
 * session information, such as user identifiers or sensitive data, before
 * storing it in the session storage. This encryption prevents unauthorized
 * access or tampering with the session data, enhancing the overall security
 * of the application.
 * 
 * Cross-Site Request Forgery (CSRF) Protection: This class helps protect
 * against CSRF attacks by generating and validating CSRF tokens. These
 * tokens are used to verify the authenticity of requests originating from
 * the application, preventing unauthorized actions by malicious actors.
 *
 * @var int			LENGTH		ID compliance
 * @var string		KEYWORD		cookie key
 * @var string		ALGOCRYPT	name of the algorithm used for encrypting the session
 * @var	string		PASSCRYPT	passphrase to encrypt the session
 * 
 * @method EntitySessionInterface|false	startSession() initializes a session
 * @method bool restartSession(EntitySessionInterface $session, EntityUserInterface $user) updates session's user
 * @method ?EntitySessionInterface createSession(?EntityUserSession $user) creates a new session and stores it in the database
 * @method bool destroySession(EntitySessionInterface $session)	deletes the session of the database and removes the cookie
 * 
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.6
 * @see			https://en.wikipedia.org/wiki/Advanced_Encryption_Standard
 * @see			https://www.php.net/manual/en/function.openssl-get-cipher-methods.php
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */

class SessionService extends Service
{
	private const 	LENGTH 		= 60;				# number of random bytes
	private const	KEYWORD		= "key";			# cookie key
	private const	ALGOCRYPT	= "AES-256-CBC";	# encryption algorithm
	private const	PASSCRYPT	= "odpjosjsaldiorowenokwnfdkfweke";	# this passphrase
	# is responsible for encrypting the sessions. TAKE GOOD CARE OF IT

	/**
	 * This method is responsible for encrypting the $sessionID
	 * using a secure encryption algorithm and returning the
	 * encrypted $sessionID. It takes the $sessionID as input and
	 * performs encryption to protect the $sessionID from
	 * unauthorized access or tampering.
	 * 
	 * @param	string			$sessionID
	 * @return	string|false	encrypted $sessionID in Base64 on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		private
	 * @see			https://www.php.net/manual/en/function.openssl-cipher-iv-length.php
	 * @see			https://www.php.net/manual/en/function.openssl-random-pseudo-bytes.php
	 * @see			https://www.php.net/manual/en/function.openssl-encrypt.php
	 * @see			https://www.php.net/manual/en/function.base64-encode.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	private static function encryptSessionID(string $sessionID): string|false
	{
		$ivSize	= openssl_cipher_iv_length(self::ALGOCRYPT);

		if ($ivSize === false) return false;

		// $ivSize gets the cipher init vector on success;
		// otherwise, the method returns false

		try {
			$iv		= openssl_random_pseudo_bytes($ivSize);
		} catch (\Exception $e) {
			return false;
		}

		// $iv gets a string of pseudo-random bytes on success;
		// otherwise, the method returns false

		$encrypted	= openssl_encrypt(
			$sessionID, self::ALGOCRYPT, self::PASSCRYPT, OPENSSL_RAW_DATA, $iv);

		if ($encrypted === false) return false;

		// $encrypted gets the encrypted $sessionID on success;
		// otherwise, the method returns false

		$encrypted	= base64_encode($iv . $encrypted);

		return $encrypted;
	}

	/**
	 * This method is responsible for decrypting an encrypted $sessionID
	 * in Base64 and returning the original plain text $sessionID. It
	 * takes the encrypted $sessionID in Base64 as input and performs
	 * the decryption process to retrieve the original $sessionID.
	 * 
	 * @param	string			$sessionIDEncrypted in Base64
	 * @return	string|false	decrypted $sessionID on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		private
	 * @see			https://www.php.net/manual/en/function.base64-decode.php
	 * @see			https://www.php.net/manual/en/function.openssl-cipher-iv-length.php
	 * @see			https://www.php.net/manual/en/function.substr.php
	 * @see			https://www.php.net/manual/en/function.openssl-decrypt.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	private static function decryptSessionID(string $sessionIDEncrypted): string|false
	{
		$data	= base64_decode($sessionIDEncrypted);

		if ($data === false) return false;

		// $data gets the $iv and encrypted $sessionID
		// concatenated on success; otherwise, the
		// method returns false

		$ivSize = openssl_cipher_iv_length(self::ALGOCRYPT);

		if ($ivSize === false) return false;

		// $ivSize gets the cipher init vector length on success;
		// otherwise, the method returns false

		$iv			= substr($data,	0,	$ivSize);
		$encrypted	= substr($data,	$ivSize);

		// extract the $iv and encrypted $sessionID

		$decrypted	= openssl_decrypt(
			$encrypted, self::ALGOCRYPT, self::PASSCRYPT, OPENSSL_RAW_DATA, $iv);
		
		return $decrypted;
	}

	/**
	 * This method is responsible for generating a unique identifier
	 * with a low probability of being duplicated and safe.
	 * 
	 * @return string|false $sessionID on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		private
	 * @see			https://www.php.net/manual/en/function.random-bytes.php
	 * @see			https://www.php.net/manual/en/function.bin2hex.php
	 * @see			https://www.php.net/manual/en/function.hash.php
	 * @see			https://www.php.net/manual/en/function.hash-algos.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	private static function generateSessionID(): string|false
	{
		try
		{
			$bytes		= random_bytes(self::LENGTH);
		} catch (\Random\RandomException $e)
		{
			$log = new ServiceLog();

			$log->setFile(__FILE__);
			$log->setLine(__LINE__);
			$log->store("Couldn't find random bytes to generate sessionID # " . $e->getMessage());

			return false;
		} catch (\ValueError $e)
		{
			$log = new ServiceLog();

			$log->setFile(__FILE__);
			$log->setLine(__LINE__);
			$log->store("SessionService::LENGTH cannot be less than 1 # " . $e->getMessage());

			return false;
		}

		// $bytes gets self::LENGTH random bytes on success

		$sessionID 	= bin2hex($bytes);

		// $sessionID gets an ASCII string containing the hex repr of $bytes

		return hash("sha256", $sessionID);
	}

	/**
	 * This method is responsible for initiating a new session for the user
	 * of the application.
	 * 
	 * @return EntitySessionInterface|false Session on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.7
	 * @access		public
	 * @see			https://www.php.net/manual/en/reserved.variables.cookies.php
	 * @see			https://www.php.net/manual/en/function.is-null.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public static function startSession(): EntitySessionInterface|false
	{
		$cookie = $_COOKIE[self::KEYWORD] ?? null;

		if (is_null($cookie) || ($sessionId = self::decryptSessionID($cookie)) === false) {
			return self::createSession(null) ?? false;
		}

		try {
			$sessionReflect = new \ReflectionClass(EntitySessionInterface::class);
			$sessionAttr	= $sessionReflect->getAttributes()[0];
			$sessionReflect	= new \ReflectionClass($sessionAttr->getName());
			$session		= $sessionReflect->getMethod('newInstance')
				->invoke(null, $sessionId);
		} catch (\ReflectionException $e) {
			return false;
		}

		if (is_null($session) || $session->isExpired())
			return self::createSession(null) ?? false;

		return $session ?? false;
	}

	/**
	 * This method is responsible for restarting an existing session for a
	 * specified user. It takes a session object and a user object as input
	 * and performs necessary actions to restart the session, ensuring
	 * continuity and maintaining session-related data and variables.
	 * 
	 * @param 	EntitySessionInterface	$session Session
	 * @param 	EntityUserInterface		$user New user
	 * 
	 * @return	bool true on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.3
	 * @access		public
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public static function restartSession(EntitySessionInterface $session,
		EntityUserInterface $user): bool
	{
		$session->setUser($user);

		return $session->flush();
	}

	/**
	 * This method is responsible for creating a new session for either a user
	 * of the application. It takes a user as input and performs necessary
	 * actions to initiate a new session and returns it.
	 * 
	 * @param 	?EntityUserInterface $user
	 * 
	 * @return	?EntitySessionInterface Session on success; null otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.4
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.setcookie.php
	 * @see			https://www.php.net/manual/en/book.reflection.php
	 * @see			https://www.php.net/manual/en/class.reflectionexception.php
	 * @see			https://www.php.net/manual/en/class.outofrangeexception.php
	 * @see			https://www.php.net/manual/en/class.invalidargumentexception.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public static function createSession(?EntityUserInterface $user): ?EntitySessionInterface
	{
		$sessionId	= self::generateSessionID();

		try {
			$requestReflec	= new \ReflectionClass(EntityRequestInterface::class);
			$requestAttr	= $requestReflec->getAttributes()[0];
			$requestReflec	= new \ReflectionClass($requestAttr->getName());
			$sessionRequest = $requestReflec->newInstance(null, __IP__, __PORT__,
				new EntityDateTime);

			$userReflec	= new \ReflectionClass(EntitySessionInterface::class);
			$userAttr	= $userReflec->getAttributes()[0];
			$userReflec = new \ReflectionClass($userAttr->getName());
			$session	= $userReflec->newInstance($sessionId, $user,
				$sessionRequest);
		} catch (\ReflectionException | \OutOfRangeException | \InvalidArgumentException) {
			return null;
		}

		if ($session->flush()) {
			$sessionIDEncrypted = self::encryptSessionID($sessionId);
			setcookie(SessionService::KEYWORD, $sessionIDEncrypted);
			return $session;
		}

		return null;
	}

	/**
	 * This method is responsible for destroying or terminating an existing
	 * session within the application. It takes a session object as input and
	 * performs necessary actions to invalidate and remove the session, clearing
	 * any associated session data and variables.
	 * 
	 * @param EntitySessionInterface $session Session
	 * 
	 * @return	bool true on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.3
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.setcookie.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public static function destroySession(EntitySessionInterface $session): bool
	{
		return $session->killme() && setcookie(SessionService::KEYWORD);
	}
}
