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

use Josevaltersilvacarneiro\Html\Src\Enums\EntityState;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\UniqueAttributeInterface;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\
    PrimaryKeyAttributeInterface;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Exceptions\EntityExceptionInterface;

/**
 * All entities must implement this interface.
 * 
 * Note that all properties of the entity must be equal to the names
 * of the columns in the database.
 * 
 * @category  EntityInterface
 * @package   Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\EntityInterface
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.4
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Entities
 */
interface EntityInterface
{
    /**
     * This method must return the name of the property that
     * represents the primary key of the entity.
     * 
     * @return string The name of the property that represents the primary key
     */
    public static function getIdName(): string;

    /**
     * This method must return the name of the property that
     * represents the unique identifier of the entity.
     * 
     * @param mixed $value The value of the unique identifier
     * 
     * @return string The name of the property that represents the unique identifier
     */
    public static function getUniqueName(mixed $value): string;

    /**
     * This method sets the state of the entity after its attributes
     * are modified or it's persisted.
     * 
     * @param EntityState $state Modified, persisted or deleted
     * 
     * @return static $this
     */
    public function setState(EntityState $state): static;

    /**
     * This method returns the current state of the entity, i.e.,
     * if it's new, modified, persisted or deleted.
     * 
     * @return EntityState
     */
    public function getState(): EntityState;

    /**
     * This method returns the unique identifier of the entity.
     * 
     * Note that all entities must have a unique identifier.
     * 
     * @return PrimaryKeyAttributeInterface
     * @throws EntityExceptionInterface if the entity doesn't have a id
     */
    public function getId(): PrimaryKeyAttributeInterface;

    /**
     * This method create a new instance this entity based on $uid.
     * 
     * According to the needs of the entity, logic can be applied to
     * accept multiple $uid, such as email and a string of characters.
     * 
     * @param UniqueAttributeInterface $uid UNIQUE CONSTRAINT
     * 
     * @return static|null $this on success; null on failure
     */
    public static function newInstance(UniqueAttributeInterface $uid): ?static;

    /**
     * This method persists the entity in the database.
     * 
     * @return bool true on success; false on failure
     */
    public function flush(): bool;

    /**
     * This method deletes the entity from the database.
     * 
     * Note that according to needs of the project, this method
     * can only change an attribute in the database, informing
     * that the entity is inactive.
     * 
     * @return bool true on success; false on failure
     */
    public function killme(): bool;
}
