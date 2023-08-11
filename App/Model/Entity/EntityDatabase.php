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

use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityDatabaseInterface;
use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityState;

/**
 * This class defines the basic structure and functionality for database entities,
 * enforcing controlled access to specific properties and states and providing a
 * convenient way to interact with the database through the EntityManager class.
 * Concrete subclasses that extend this class must implement certain abstract
 * methods to tailor the behavior to their specific entity requirements.
 *
 * @var EntityState $state Entity's status
 * 
 * @method void set(mixed & $ref, mixed $value)	Manages properties
 * @method void setSTATE(EntityState $state) Sets the state
 * @method void setID(string|int $id) Sets the ID
 * 
 * @method EntityState	getSTATE() Returns the Entity's state
 * @method string|int	getID() Returns the Entity's ID
 * @method string getIDNAME() Returns the property's name that is the ID
 * @method string getUNIQUE(mixed $uID) Returns the field's name based on $uID
 * @method mixed getDatabaseRepresentation() Returns the database's representation
 * 
 * @method ?static newInstance(string|int $UID) Returns a new instance of this Entity
 * @method bool flush() Synchronizes with the database
 * @method bool killme() Deletes this Entity
 * 
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.2
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */

abstract class EntityDatabase implements EntityDatabaseInterface
{
    private EntityState $state = EntityState::TRANSIENT; # used for stored purposes

	public function __construct()
	{}

	/**
	 * This method is used to set the value of a property and change the state
	 * of the EntityDatabase object to EntityState::DETACHED.
	 * 
	 * @param mixed &$ref	Var passed by reference that will be updated by $value
	 * @param mixed	$value	The new value to be assigned to $ref
	 * 
	 * @return void
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		protected
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	protected function set(mixed & $ref, mixed $value): void
	{
		$ref = $value;
		$this->setSTATE(EntityState::DETACHED);
	}

	/**
	 * This method is used to retrieve the name of the calling class (the class
	 * that called this method).
	 * 
	 * @return string	Name of the calling class
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		private
	 * @see			https://www.php.net/manual/en/function.debug-backtrace.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	private function getCallingClass(): string
	{
		$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);

		return $trace[2]['class'];
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
	 * @return void
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

    public function setSTATE(EntityState $state): void
    {
		if ($state !== EntityState::DETACHED &&
			$this->getCallingClass() !== EntityManager::class) return ;

		// only the EntityManager class can modify $state property
		// to EntityState::PERSISTENT or EntityState::REMOVED

        if ($state === EntityState::TRANSIENT)
            return ;

        # it's not possible to update the $state property
        # to EntityState::TRANSIENT

		$this->state = $state;
    }

	/**
	 * This method allows the EntityManager class to modify the ID property of an
	 * entity using reflection while ensuring that other classes cannot directly
	 * set the ID. Any potential errors during the process are gracefully ignored,
	 * but the main goal is to provide controlled access to updating the ID
	 * property.
	 * 
	 * @param string|int $id Entity's identifier
	 * 
	 * @return void
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @see			https://www.php.net/manual/en/class.reflectionobject.php
	 * @see			https://www.php.net/manual/en/class.reflectionexception.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

    public function setID(string|int $id): void
    {
        if ($this->getCallingClass() !== EntityManager::class)
			return ;

		// only the EntityManager class can modify the
		// ID of an entity

		try {
			$myself = new \ReflectionObject($this);
			$proper = $myself->getProperty($this->getIDNAME());
			$proper->setValue($this, $id);
		} catch (\ReflectionException) {}	// ignore failures
    }

    public function getSTATE(): EntityState
    {
        return $this->state;
    }

	/**
	 * This method allows external code to retrieve the ID property of an entity
	 * using reflection, ensuring that the ID can be accessed but not directly
	 * modified by external entities. If any issues occur during the process,
	 * such as an invalid or missing ID property, the method gracefully returns
	 * an empty string as a default value.
	 * 
	 * @return string|int Entity's identifier on success; empty string otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @see			https://www.php.net/manual/en/class.reflectionobject.php
	 * @see			https://www.php.net/manual/en/class.reflectionexception.php
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function getID(): string|int
	{
		try {
			$myself = new \ReflectionObject($this);
			$proper = $myself->getProperty($this->getIDNAME());
			return $proper->getValue($this);
		} catch (\ReflectionException) { return ''; }
	}

	/**
	 * This method serves as a placeholder for retrieving the name of the ID
	 * property in entities. Concrete subclasses that inherit from the abstract
	 * class must implement this method and return the actual name of the ID
	 * property specific to each entity.
	 * 
	 * @return string Property's name that represents the ID
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

    public abstract static function getIDNAME(): string;

	/**
	 * This method is responsible for obtaining the database representation of the
	 * entity, specifically its ID property value. By utilizing the getIDNAME
	 * method, it can dynamically access the ID property's value based on the name
	 * returned by getIDNAME() implemented in the concrete subclasses.
	 * 
	 * @return mixed ID field value
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.2
	 * @access		public
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

    public function getDatabaseRepresentation(): mixed
    {
        return $this->getID();
    }

	/********************************************************/
	/************************* CRUD *************************/

	/**
	 * This method is a convenient static factory method that allows creating a
	 * new instance of the entity class based on a unique identifier. It
	 * delegates the instantiation process to the EntityManager class, which
	 * handles the initialization and interaction with the database entities
	 * through their corresponding DAO.
	 * 
	 * @param string|int $UID Unique identifier
	 * 
	 * @return ?static Entity object on success; null otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public static function newInstance(string|int $UID): ?static
	{
		try { return EntityManager::init(static::class, $UID); }
		catch (EntityManagerException) { return null; }
	}

	/**
	 * This method provides a convenient way to trigger the synchronization of
	 * the current EntityDatabase object with the underlying database. It
	 * delegates the synchronization process to the EntityManager class, which
	 * handles the synchronization and interaction with the database entities
	 * through their corresponding DAO.
	 * 
	 * @return bool true on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function flush(): bool
	{
		try { return EntityManager::flush($this); }
		catch (EntityManagerException) { return false; }
	}

	/**
	 * This method provides a convenient way to delete the current EntityDatabase
	 * object from the database. It delegates the deletion process to the
	 * EntityManager class, which handles the interaction with the database
	 * entities through their corresponding DAO.
	 * 
	 * @return bool true on success; false otherwise
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.1
	 * @access		public
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public function killme(): bool
	{
		return EntityManager::del($this);
	}

	/**
	 * This method is responsible for returning the name of the field that
	 * represents a unique constraint in the entity, based on the provided
	 * $uID parameter.
	 * 
	 * Within the method, there is logic to determine the field's name that
	 * represents the unique constraint in the entity. The specific
	 * implementation of this logic depends on the structure and design of
	 * the entity class.
	 * 
	 * The $uID parameter serves as a reference or identifier to identify
	 * the specific unique constraint in the entity. Its value may vary
	 * depending on the context or specific requirements of the project.
	 * 
	 * The method returns a string representing the name of the field that
	 * represents the unique constraint in the entity, based on the
	 * provided $uID parameter.
	 * 
	 * @param mixed $uID Possible value for this field @example git@josevaltersilvacarneiro.net
	 * 
	 * @return string The field @example tbEmail
	 * 
	 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version		0.2
	 * @access		public
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public abstract static function getUNIQUE(mixed $uID): string;
}
