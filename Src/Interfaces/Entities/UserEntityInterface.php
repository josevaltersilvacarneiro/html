<?php

declare(strict_types=1);

/**
 * Based on the project requirements, the interfaces will be written.
 * PHP VERSION >= 8.2.0
 * 
 * Copyright (C) 2023, José Carneiro
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
 * @category Entities
 * @package  Josevaltersilvacarneiro\Html\Src\Interfaces\Entities
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Entities
 */

namespace Josevaltersilvacarneiro\Html\Src\Interfaces\Entities;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\{
    EntityWithIncrementalPrimaryKeyInterface};

use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\NameAttributeInterface;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\EmailAttributeInterface;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\HashAttributeInterface;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Exceptions\EntityExceptionInterface;

/**
 * This interface represents the user entity.
 * 
 * @category  UserEntityInterface
 * @package   Josevaltersilvacarneiro\Html\Src\Interfaces\Entities
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.2
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Entities
 */
interface UserEntityInterface extends EntityWithIncrementalPrimaryKeyInterface
{
    /**
     * Sets the user's full name.
     * 
     * @param NameAttributeInterface $fullname The user's full name
     * 
     * @return static itself
     * @throws EntityExceptionInterface if the full name is invalid
     */
    public function setFullname(NameAttributeInterface $fullname): static;

    /**
     * Sets the user's email.
     * 
     * @param EmailAttributeInterface $email The user's email
     * 
     * @return static itself
     * @throws EntityExceptionInterface if the email is invalid
     */
    public function setEmail(EmailAttributeInterface $email): static;

    /**
     * Sets the user's password.
     * 
     * @param HashAttributeInterface $hash The user's password hash
     * 
     * @return static itself
     * @throws EntityExceptionInterface if the password is invalid
     */
    public function setPassword(
        HashAttributeInterface $hash
    ): static;

    /**
     * Gets the user's full name.
     * 
     * @return NameAttributeInterface The user's full name
     */
    public function getFullname(): NameAttributeInterface;

    /**
     * Gets the user's email.
     * 
     * @return EmailAttributeInterface The user's email
     */
    public function getEmail(): EmailAttributeInterface;

    /**
     * Gets the user's hash hash.
     * 
     * @return HashAttributeInterface The user's hash
     */
    public function getHash(): HashAttributeInterface;

    /**
     * Informs if the user is active.
     * 
     * @return bool True if the user is active; false otherwise
     */
    public function isActive(): bool;
}
