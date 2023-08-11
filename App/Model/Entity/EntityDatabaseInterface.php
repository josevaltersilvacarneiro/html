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

use Josevaltersilvacarneiro\Html\App\Model\Entity\Entity;
use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityState;

interface EntityDatabaseInterface extends Entity
{
    public static function getIDNAME(): string;
    public static function getUNIQUE(mixed $uID): string;

    public function setSTATE(EntityState $state);
    public function setID(string|int $id);

    public function getSTATE(): EntityState;
    public function getID(): string|int;

    public static function newInstance(string|int $UID): ?static;
    public function flush(): bool;
    public function killme(): bool;
}
