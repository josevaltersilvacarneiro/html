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

use Josevaltersilvacarneiro\Html\Src\Interfaces\Dependency\DependencyInterface;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\EntityInterface;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\UserEntityInterface;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\RequestEntityInterface;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Exceptions\EntityExceptionInterface;

/**
 * This interface represents the session entity.
 * 
 * @category  SessionEntityInterface
 * @package   Josevaltersilvacarneiro\Html\Src\Interfaces\Entities
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.3
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Entities
 */
interface SessionEntityInterface extends EntityInterface, DependencyInterface
{
    public const KEYWORD = 'session';

    // @example __construct(IncrementalPrimaryKeyAttributeInterface $pk,
    //      UserEntityInterface $user, RequestEntityInterface $request);

    /**
     * Sets a new user for the session.
     * 
     * @param UserEntityInterface $user The user entity
     * 
     * @return static itself
     * @throws EntityExceptionInterface If the user is not logged in
     */
    public function setUser(UserEntityInterface $user): static;

    /**
     * Sets the last request that used this session.
     * 
     * @param RequestEntityInterface $request The request entity
     * 
     * @return static itself
     * @throws EntityExceptionInterface If the request is not valid
     */
    public function setRequest(RequestEntityInterface $request): static;

    /**
     * Returns the user entity.
     * 
     * @return UserEntityInterface|null The user if he is logged in; null otherwise
     */
    public function getUser(): ?UserEntityInterface;

    /**
     * Returns the request entity.
     * 
     * @return RequestEntityInterface The last request
     */
    public function getRequest(): RequestEntityInterface;

    /**
     * Informs if the session is expired.
     * 
     * @return bool True if the session is expired; false otherwise
     */
    public function isExpired(): bool;

    /**
     * Informs if the user is logged in.
     * 
     * @return bool True if the user is logged in; false otherwise
     */
    public function isUserLogged(): bool;
}
