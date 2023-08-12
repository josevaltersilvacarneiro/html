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
use Josevaltersilvacarneiro\Html\App\Model\Entity\AppEntity\UserSession;
use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityDatabaseInterface;
use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityUserInterface;
use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityRequestInterface;

#[UserSession]
interface EntitySessionInterface extends EntityDatabaseInterface
{
    public function __construct(string $id, ?EntityUserInterface $user,
        EntityRequestInterface $request);

    public function setUser(EntityUserInterface $user): void;
    public function setRequest(EntityRequestInterface $request): void;
    public function getUser(): ?EntityUserInterface;
    public function getRequest(): EntityRequestInterface;
    public function isExpired(): bool;
    public function isUserLogged(): bool;
}
