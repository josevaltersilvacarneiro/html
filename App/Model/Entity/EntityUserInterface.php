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
use Josevaltersilvacarneiro\Html\App\Model\Entity\AppEntity\User;
use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityDatabaseInterface;

#[User]
interface EntityUserInterface extends EntityDatabaseInterface
{
    public function __construct(?int $id, string $fullname, string $email,
        string $hash, string $salt, bool $active);

    public function setFullname(string $fullname): void;
    public function setEmail(string $email): void;
    public function setPassword(string $hash, string $salt);

    public function getFullname(): string;
    public function getEmail(): string;
    public function getHash(): string;
    public function getSalt(): string;

    public function isActive(): bool;
}
