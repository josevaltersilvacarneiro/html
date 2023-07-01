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
 * The package offers services for managing relationships between
 * entities in the database. It provides utilities for defining
 * and enforcing relationships such as one-to-one, one-to-many,
 * or many-to-many associations. It simplifies the handling of
 * entity relationships and supports efficient data retrieval
 * across related entities.
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

use Josevaltersilvacarneiro\Html\App\Model\Entity\{Session, Visitor, User};
use Josevaltersilvacarneiro\Html\App\Model\Service\{Service, UserService};
use Josevaltersilvacarneiro\Html\App\Model\Dao\SessionDao;
use Josevaltersilvacarneiro\Html\Src\Classes\Log\ServiceLog;

/**
 * The Session class is responsible for providing services to
 * create, manage, and destroy sessions within the application.
 * It offers functionalities to handle session data, ensuring
 * its security by encrypting and decrypting the session
 * information.
 * 
 * Session Creation and Destruction: The Session class facilitates
 * the creation of new sessions when a user initiates a session
 * with the application. It generates a unique session ID for each
 * user and initializes the session data. Additionally, it
 * provides methods to destroy sessions and remove session data
 * when a user logs out.
 * 
 * Session Data Management: The class offers services for managing
 * session data throughout the user's interaction with your application.
 * It allows you to store and retrieve information specific to each
 * user's session, such as user preferences, authentication details, or
 * temporary data required during the session's lifespan.
 * 
 * Session Encryption: The Session class ensures the security and privacy
 * of session data by providing encryption services. It encrypts the
 * session information, such as user identifiers or sensitive data, before
 * storing it in the session storage. This encryption prevents unauthorized
 * access or tampering with the session data, enhancing the overall security
 * of the application.
 * 
 * Cross-Site Request Forgery (CSRF) Protection: The Session class helps
 * protect against CSRF attacks by generating and validating CSRF tokens.
 * These tokens are used to verify the authenticity of requests originating
 * from your application, preventing unauthorized actions by malicious
 * actors.
 *
 * @var int			LENGTH		ID compliance
 * @var string		KEYWORD		cookie key
 * @var string		ALGOCRYPT	name of the algorithm used for encrypting the session
 * @var	string		PASSCRYPT	passphrase to encrypt the session
 *
 * @method Session|false	startSession()	initializes the session
 * @method bool				restartSession(Session $session, User $user)	updates session's user
 * @method string|false		createSession(User|Visitor $user)	creates a new session and stores it in the database
 * @method bool				destroySession(Session $session)	updates sessionON field to false
 * 
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.1
 * @see			https://en.wikipedia.org/wiki/Advanced_Encryption_Standard
 * @see			https://www.php.net/manual/en/function.openssl-get-cipher-methods.php
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */

class SessionService extends Service
{
	private const 	LENGTH 		= 60;				# generates 120 characters
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
	 * This method is responsible for initiating a new session for
	 * a user within the application. It performs necessary actions
	 * to start a session, such as creating a $sessionID, setting
	 * session variables, and initializing session-specific data.
	 * 
	 * @return Session|false $session on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @see			https://www.php.net/manual/en/reserved.variables.cookies.php
	 * @see			https://www.php.net/manual/en/class.datetimeimmutable.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public static function startSession(): Session|false
	{
		$cookie			= $_COOKIE[self::KEYWORD];
		$sessionID		= $cookie ? self::decryptSessionID($cookie) :
			self::createSession(
				new Visitor()
			);

		// $sessionID is false if couldn't create a
		// session or couldn't decrypt the COOKIE
		// WARNING: could be a hacker attack
		
		if ($sessionID === false)
		{
			$sessionID = self::createSession(new Visitor);

			// try again to create a session

			if ($sessionID === false) return false;

			// it's not possible to create new sessions
		}

		$dao			= new SessionDao();

		$session		= $dao->r(
			array('sessionID' => $sessionID)
		);

		$codition = $session === false || (bool) $session['sessionON'] === false;

		if ($codition)
		{
			$sessionID = self::createSession(new Visitor());

			// $session is false if the record cannot be found in
			// the database
			// WARNING: if the used session is no longer active,
			// create a new

			$session		= $dao->r(
				array('sessionID' => $sessionID)
			);

			// try again to read a session

			if ($session === false) return false;
		
			// it's not possible to read sessions from the database
		}
		
		$user			= $session['sessionUSER'] ?
			UserService::startUser((int) $session['sessionUSER']) : null;

		$sessionID		= (string) $session['sessionID'];
		$sessionUSER	= $user;
		$sessionIP		= (string) $session['sessionIP'];
		$sessionPORT	= (string) $session['sessionPORT'];
		$sessionDATE	= new \DateTimeImmutable($session['sessionDATE']);
		$sessionON		= (bool) $session['sessionON'];
		
		return new Session(
			sessionID:		$sessionID,
			sessionUSER:	$sessionUSER,

			sessionIP:		$sessionIP,
			sessionPORT:	$sessionPORT,

			sessionDATE:	$sessionDATE,
			sessionON:		$sessionON
		);
	}

	/**
	 * This method is responsible for restarting an existing
	 * session for a specified user. It takes a Session
	 * object and a User object as input and performs necessary
	 * actions to restart the session, ensuring continuity and
	 * maintaining session-related data and variables.
	 * 
	 * @param 	Session		$session
	 * @param 	User		$user
	 * 
	 * @return	bool true on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public static function restartSession(Session $session, User $user): bool
	{
		$dao = new SessionDao();

		$newSession = array(
			'sessionID'		=>	$session->getSessionid(),
			'sessionUSER'	=>	$user->getUserid(),
		);

		if (!$dao->u($newSession)) return false;

		// if the record couldn't be updated in the
		// database, return false

		$session->setSessionuser($user);

		return true;
	}

	/**
	 * This method is responsible for creating a new session
	 * for either a User or a Visitor within the application.
	 * It takes a User or Visitor object as input and performs
	 * necessary actions to initiate a new session, generating
	 * a $sessionID and returning it.
	 * 
	 * @param 	User|Visitor $user
	 * 
	 * @return	string|false $sessioID on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.setcookie.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public static function createSession(User|Visitor $user): string|false
	{
		$sessionID		= self::generateSessionID();
		$sessionUSER	= $user instanceof User ? $user->getUserid() : null;

		$dao			= new SessionDao();

		$success		= $dao->c(array(
				'sessionID' 	=> $sessionID,
				'sessionUSER'	=> $sessionUSER,

				'sessionIP'		=> __IP__,
				'sessionPORT'	=> __PORT__,
		));
		
		if ($success) {
			$sessionIDEncrypted = self::encryptSessionID($sessionID);
			setcookie(SessionService::KEYWORD, $sessionIDEncrypted);
			return $sessionID;
		}

		return false;
	}

	/**
	 * This method is responsible for destroying or terminating
	 * an existing session within the application. It takes a
	 * Session object as input and performs necessary actions to
	 * invalidate and remove the session, clearing any associated
	 * session data and variables.
	 * 
	 * @param 	Session $session
	 * 
	 * @return	bool true on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @see			https://www.php.net/manual/en/function.setcookie.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public static function destroySession(Session $session): bool
	{
		$dao = new SessionDao();

		$session = array(
			'sessionID'	=>	$session->getSessionid(),
			'sessionON'	=>	false
		);

		return $dao->u($session) && setcookie(SessionService::KEYWORD);
	}
}
