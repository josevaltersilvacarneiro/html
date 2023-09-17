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

use Josevaltersilvacarneiro\Html\App\Model\Dao\UserDao;
use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityWithIncrementalPrimaryKey;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\UserEntityInterface;

use Josevaltersilvacarneiro\Html\App\Model\Attributes\PrimaryKeyAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\NameAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\EmailAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\HashAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\SaltAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\ActiveAttribute;

/**
 * The User Entity represents a user. It encapsulates the user's
 * attributes and provides methods for setting and retrieving
 * the user's data.
 *
 * @var ?PrimaryKeyAttribute $_id     primary key
 * @var NameAttribute        $_name   fullname @example José Carneiro
 * @var EmailAttribute       $_email  email @example git@josevaltersilvacarneiro.net
 * @var HashAttribute        $_hash   user's password hash
 * @var SaltAttribute        $_salt   password salt
 * @var ActiveAttribute      $_active true if the used is logged in; false otherwise
 * 
 * @category  User
 * @package   Josevaltersilvacarneiro\Html\App\Model\Entity
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.9.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Model/Entity
 */
#[UserDao]
class User extends EntityWithIncrementalPrimaryKey implements UserEntityInterface
{
    /**
     * Initializes the User object.
     * 
     * @param ?PrimaryKeyAttribute $_id     primary key
     * @param NameAttribute        $_name   name of the user @example José Carneiro
     * @param EmailAttribute       $_email  @example git@josevaltersilvacarneiro.net
     * @param HashAttribute        $_hash   a SHA256 hash
     * @param SaltAttribute        $_salt   random letters used to generate the hash
     * @param ActiveAttribute      $_active true if active; false otherwise
     * 
     * @return void
     */
    public function __construct(
        #[PrimaryKeyAttribute('user_id')] private ?PrimaryKeyAttribute $_id,
        #[NameAttribute('fullname')] private NameAttribute $_name,
        #[EmailAttribute('email')] private EmailAttribute $_email,
        #[HashAttribute('hash')] private HashAttribute $_hash,
        #[SaltAttribute('salt')] private SaltAttribute $_salt,
        #[ActiveAttribute('active')] private ActiveAttribute $_active
    ) {
    }

    /**
     * This method returns the name of the primary key field.
     * 
     * @return string Primary key field name
     */
    public static function getIdName(): string
    {
        return '_id';
    }

    /**
     * This method returns the name of the unique field.
     * 
     * @param mixed $value The value that represents the unique field
     * 
     * @return string If $value is a email, returns '_email'; otherwise returns '_id'
     */
    public static function getUniqueName(mixed $value): string
    {
        return
            filter_var($value, FILTER_VALIDATE_EMAIL) ? '_email' : self::getIdName();
    }

    /**
     * This method returns the name of the unique field.
     * 
     * @param mixed $uID The unique field
     * 
     * @return string The unique field
     */
    public static function getUnique(mixed $uID): string
    {
        $field = gettype($uID) === 'string' ? '_email' : self::getIdName();

        return $field;
    }

    /**
     * This method is responsible for setting the fullname.
     * 
     * @param NameAttribute $fullname Name of the user
     * 
     * @return static itself
     */
    public function setFullname(NameAttribute $fullname): static
    {
        $this->set($this->_name, $fullname);
        return $this;
    }

    /**
     * This method is responsible for setting the email.
     * 
     * @param EmailAttribute $email Email
     * 
     * @return static itself
     */
    public function setEmail(EmailAttribute $email): static
    {
        $this->set($this->_email, $email);
        return $this;
    }

    /**
     * This method is responsible for setting the password-related
     * properties (hash and salt).
     * 
     * @param HashAttribute $hash A hash
     * @param SaltAttribute $salt Random characters
     * 
     * @return static itself
     */
    public function setPassword(HashAttribute $hash, SaltAttribute $salt): static
    {
        $this->set($this->_hash, $hash);
        $this->set($this->_salt, $salt);
        return $this;
    }

    /**
     * This method returns the fullname.
     * 
     * @return NameAttribute The fullname
     */
    public function getFullname(): NameAttribute
    {
        return $this->_name;
    }

    /**
     * This method returns the email.
     * 
     * @return EmailAttribute The email
     */
    public function getEmail(): EmailAttribute
    {
        return $this->_email;
    }

    /**
     * This method returns the hash.
     * 
     * @return HashAttribute The hash
     */
    public function getHash(): HashAttribute
    {
        return $this->_hash;
    }

    /**
     * This method returns the salt.
     * 
     * @return SaltAttribute The salt
     */
    public function getSalt(): SaltAttribute
    {
        return $this->_salt;
    }

    /**
     * This method returns true if the user is logged in; false otherwise.
     * 
     * @return bool true if yes; false otherwise
     */
    public function isActive(): bool
    {
        return $this->_active->getRepresentation();
    }

    /**
     * This method is responsible for safely deactivating the current user by
     * setting the active attribute to false. It ensures that any changes
     * to the user entity are synchronized with the database before deactivation.
     * 
     * @return bool true on success; false otherwise
     */
    public function killme(): bool
    {
        $this->_active->disable();

        if ($this->flush()) {
            return true;
        }

        $this->_active = new ActiveAttribute();

        // if the flush operation returns false, the method sets the status
        // back to true, indicating that the user remains active, and returns false

        return false;
    }
}
