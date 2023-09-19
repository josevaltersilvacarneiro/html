<?php

declare(strict_types=1);

/**
 * The Entity package contains classes that represent the database
 * tables as entities. These entity classes encapsulate the structure
 * and behavior of specific tables, providing a convenient way to
 * interact with the corresponding database records.
 * PHP VERSION >= 8.2.0
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
 * @category Entity
 * @package  Josevaltersilvacarneiro\Html\App\Model\Entity
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/App/Model/Entity
 */

namespace Josevaltersilvacarneiro\Html\App\Model\Entity;

use Josevaltersilvacarneiro\Html\App\Model\Attributes\GeneratedPrimaryKeyAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Entity\Entity;
use Josevaltersilvacarneiro\Html\App\Model\Dao\UserSessionDao;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\SessionEntityInterface;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\UserEntityInterface;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\RequestEntityInterface;

use Josevaltersilvacarneiro\Html\App\Model\Attributes\DateAttribute;

use Josevaltersilvacarneiro\Html\Src\Traits\CryptTrait;

/**
 * This class represents a session. It contains properties and methods to manage
 * session-related data and operations.
 * 
 * @var GeneratedPrimaryKeyAttribute $_sessionId  primary key
 * @var ?UserEntityInterface         $_sessionUser foreign key
 * @var RequestEntityInterface       $_request     foreign key
 * 
 * @category  Session
 * @package   Josevaltersilvacarneiro\Html\App\Model\Entity
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.9.3
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Model/Entity
 */
#[UserSessionDao]
final class Session extends Entity implements SessionEntityInterface
{
    use CryptTrait;

    private const PASSCRYPT = 'odpjosjsaldiorowenokwnfdkfweke';
    private const NUMBER_OF_DAYS_TO_EXPIRE = 1;

    /**
     * This constructor is responsible for initializing a Session object
     * with the provided values.
     * 
     * @param GeneratedPrimaryKeyAttribute $_sessionId   primary key
     * @param ?UserEntityInterface         $_sessionUser foreign key
     * @param RequestEntityInterface       $_request     foreign key
     * 
     * @return void
     */
    public function __construct(
        #[GeneratedPrimaryKeyAttribute("session_id")] private
        GeneratedPrimaryKeyAttribute $_sessionId,
        #[User("sessionuser")] private ?UserEntityInterface $_sessionUser,
        #[Request("request")] private RequestEntityInterface $_request
    ) {
    }

    /**
     * This method returns the name of the primary key property.
     * 
     * @return string Primary key property name
     */
    public static function getIdName(): string
    {
        return '_sessionId';
    }

    /**
     * This method is responsible for setting the user session property,
     * while also validating the user's activity status.
     * 
     * It ensures that the provided user object represents an active user by
     * invoking the isActive method. This validation helps maintain data
     * integrity and ensures that only valid user objects are assigned.
     * 
     * @param UserEntityInterface $user New user
     * 
     * @return static $this
     * @throws \InvalidArgumentException If the provided user isn't active
     */
    public function setUser(UserEntityInterface $user): static
    {
        if ($this->isUserLogged() || !$user->isActive()) {
            throw new \InvalidArgumentException(
                "This session belongs to another user or
				{$user->getFullname()->getRepresentation()} isn't active",
                1
            );
        }

        $this->set($this->_sessionUser, $user);
        return $this;
    }

    /**
     * This method is responsible for setting the session request,
     * while also validating if the request belonging to the session is
     * older than the current.
     * 
     * @param RequestEntityInterface $request New Request
     * 
     * @return static $this
     * @throws \InvalidArgumentException If the request is old
     */
    public function setRequest(RequestEntityInterface $request): static
    {
        if ($this->getRequest()->getDate() > $request->getDate()) {
            throw new \InvalidArgumentException("The request is old", 1);
        }

        $this->set($this->_request, $request);
        return $this;
    }

    /**
     * This method returns the user entity.
     * 
     * @return UserEntityInterface|null The user if he is logged in; null otherwise
     */
    public function getUser(): ?UserEntityInterface
    {
        return $this->_sessionUser;
    }

    /**
     * This method returns the request entity.
     * 
     * @return RequestEntityInterface The last request
     */
    public function getRequest(): RequestEntityInterface
    {
        return $this->_request;
    }

    /**
     * This method informs if the session is expired.
     * 
     * @return bool True if yes; false otherwise
     */
    public function isExpired(): bool
    {
        return $this->getRequest()->getDate()
            ->diff(new DateAttribute, true)->days > self::NUMBER_OF_DAYS_TO_EXPIRE;
    }

    /**
     * This method informs if the user is logged in.
     * 
     * @return bool True if yes; false otherwise
     */
    public function isUserLogged(): bool
    {
        return !is_null($this->getUser());
    }

    /**
     * This method is responsible for creating a new session.
     * 
     * @param array $dependencies Dependencies
     * 
     * @return static|false A new session on success; false otherwise
     */
    public static function fork(array $dependencies = []): static|false
    {
        $cookie = $_COOKIE[self::KEYWORD] ?? null;

        if (is_null($cookie) 
            || ($sessionId = self::_decrypt($cookie, self::PASSCRYPT)) === false
        ) {
            return self::_createSession() ?? false;
        }

        try {
            $sessionReflect = new \ReflectionClass(EntitySessionInterface::class);
            $sessionAttr    = $sessionReflect->getAttributes()[0];
            $sessionReflect = new \ReflectionClass($sessionAttr->getName());
            $session        = $sessionReflect->getMethod('newInstance')
                ->invoke(null, $sessionId);
        } catch (\ReflectionException) {
            return false;
        }

        if (is_null($session) || $session->isExpired()) {
            return self::_createSession() ?? false;
        }

        return $session ?? false;
    }

    /**
     * This method is responsible for creating a new session
     * on the database.
     * 
     * @return static|null A new session on success; null otherwise
     */
    private static function _createSession(): ?static
    {
        $sessionId = GeneratedPrimaryKeyAttribute::generatePrimaryKey();

        try {
            $requestReflec  = new \ReflectionClass(EntityRequestInterface::class);
            $requestAttr    = $requestReflec->getAttributes()[0];
            $requestReflec  = new \ReflectionClass($requestAttr->getName());
            $sessionRequest = $requestReflec->newInstance(
                null, __IP__, __PORT__,
                new DateAttribute
            );

            $userReflec    = new \ReflectionClass(EntitySessionInterface::class);
            $userAttr    = $userReflec->getAttributes()[0];
            $userReflec = new \ReflectionClass($userAttr->getName());
            $session    = $userReflec->newInstance(
                $sessionId, null,
                $sessionRequest
            );
        } catch (\ReflectionException | \OutOfRangeException |
        \InvalidArgumentException) {
            return null;
        }

        if ($session->flush()) {
            $sessionIdEncrypted = self::_encrypt($sessionId, self::PASSCRYPT);
            setcookie(self::KEYWORD, $sessionIdEncrypted);
            return $session;
        }

        return null;
    }
}
