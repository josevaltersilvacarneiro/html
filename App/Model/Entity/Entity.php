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

use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\EntityInterface;

use Josevaltersilvacarneiro\Html\Src\Enums\EntityState;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\UniqueAttributeInterface;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\{
    PrimaryKeyAttributeInterface};

use Josevaltersilvacarneiro\Html\Src\Classes\EntityManager\EntityManager;

use Josevaltersilvacarneiro\Html\Src\Classes\Exceptions\EntityException;
use Josevaltersilvacarneiro\Html\Src\Classes\Exceptions\EntityManagerException;

/**
 * This class defines the basic structure and functionality for entities,
 * enforcing controlled access to specific properties and states and providing a
 * convenient way to interact with the database through the EntityManager.
 * 
 * @var EntityState $_state Entity's status
 * 
 * @method static setState(EntityState $state) Sets the state
 * 
 * @method EntityState getState() Returns the entity's state
 * @method string|int  getId()    Returns the entity's id
 * 
 * @method ?static newInstance(UniqueAttributeInterface $uid)
 * @method bool flush()  Synchronizes with the database
 * @method bool killme() Deletes this Entity
 * 
 * @category  Entity
 * @package   Josevaltersilvacarneiro\Html\App\Model\Entity
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.4.5
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Model/Entity
 */
abstract class Entity implements EntityInterface
{
    private EntityState $_state = EntityState::TRANSIENT; // used for stored purposes

    /**
     * This method is used to set the value of a property and change the state
     * of the EntityDatabase object to EntityState::DETACHED.
     * 
     * @param mixed $ref   The reference that will be updated by $value
     * @param mixed $value The new value to be assigned to $ref
     * 
     * @return void
     */
    protected function set(mixed & $ref, mixed $value): void
    {
        $ref = $value;
        $this->setState(EntityState::DETACHED);
    }

    /**
     * This method is used to retrieve the name of the class that called
     * this method.
     * 
     * @return string Name of the calling class
     */
    protected function getCallingClass(): string
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);

        return $trace[2]['class'];
    }

    /**
     * This method is used to retrieve the name of the field that represents
     * a unique identifier (or constraint) for the entity.
     * 
     * @param mixed $value Value used to retrieve the name of the field
     * 
     * @return string Unique constraint name
     */
    public static function getUniqueName(mixed $value): string
    {
        return static::getIdName();
    }

    /**
     * This method acts as a controlled setter for the entity state, allowing
     * the EntityManager class to update the state to EntityState::PERSISTENT
     * or EntityState::REMOVED, while preventing other classes from setting it
     * directly to EntityState::PERSISTENT, EntityState::REMOVED, or
     * EntityState::TRANSIENT.
     * 
     * @param EntityState $state New Entity status
     * 
     * @return static $this
     */
    public function setState(EntityState $state): static
    {
        if ($state !== EntityState::DETACHED 
            && $this->getCallingClass() !== EntityManager::class
        ) {
            return $this;
        }

        // only the EntityManager class can modify $state property
        // to EntityState::PERSISTENT or EntityState::REMOVED

        if ($state === EntityState::TRANSIENT) {
            return $this;
        }

        // it's not possible to update the $state property
        // to EntityState::TRANSIENT

        $this->_state = $state;
        return $this;
    }

    /**
     * Returns the state of this entity.
     * 
     * @return EntityState Entity's status
     */
    public function getState(): EntityState
    {
        return $this->_state;
    }

    /**
     * This method allows external code to retrieve the ID property of an entity
     * using reflection, ensuring that the ID can be accessed but not directly
     * modified by external entities. If any issues occur during the process,
     * such as an invalid or missing ID property, the method gracefully returns
     * an empty string as a default value.
     * 
     * @return ?PrimaryKeyAttributeInterface Entity's identifier on success; empty string otherwise
     * @throws EntityExceptionInterface      if the entity doesn't have a id
     */
    public function getId(): ?PrimaryKeyAttributeInterface
    {
        try {
            $myself = new \ReflectionObject($this);
            $proper = $myself->getProperty($this->getIdName());
            return $proper->getValue($this);
        } catch (\ReflectionException $e) {
            throw new EntityException(
                'The entity ' . static::class . ' doesn\'t have a id',
                $e
            );
        }
    }

    /**
     * This method is a convenient static factory method that allows creating a
     * new instance of the entity class based on a unique identifier. It
     * delegates the instantiation process to the EntityManager class, which
     * handles the initialization and interaction with the database through their
     * corresponding DAO.
     * 
     * @param UniqueAttributeInterface $uid Unique identifier
     * 
     * @return ?static Entity object on success; null otherwise
     */
    public static function newInstance(UniqueAttributeInterface $uid): ?static
    {
        try {
            return EntityManager::init(static::class, $uid);
        } catch (EntityManagerException $e) {
            $e->storeLog();
            return null;
        }
    }

    /**
     * This method provides a convenient way to trigger the synchronization of
     * the current Entity object with the database. It delegates the synchronization
     * process to the EntityManager class, which handles the synchronization and
     * interaction with the database through their corresponding DAO.
     * 
     * @return bool true on success; false otherwise
     */
    public function flush(): bool
    {
        try {
            return EntityManager::flush($this);
        } catch (EntityManagerException $e) {
            $e->storeLog();
            return false;
        }
    }

    /**
     * This method provides a convenient way to delete the current Entity object
     * from the database. It delegates the deletion process to the EntityManager
     * class, which handles the interaction with the database through their
     * corresponding DAO.
     * 
     * @return bool true on success; false otherwise
     */
    public function killme(): bool
    {
        return EntityManager::del($this);
    }
}
