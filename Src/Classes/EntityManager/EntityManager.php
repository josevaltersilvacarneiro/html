<?php

declare(strict_types=1);

/**
 * This package provides a ORM (Object-Relational Mapping), whose role
 * is to map entities with tables in the database.
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
 * @category EntityManager
 * @package  Josevaltersilvacarneiro\Html\Src\Classes\EntityManager
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Classes/EntityManager
 */

namespace Josevaltersilvacarneiro\Html\Src\Classes\EntityManager;

use Josevaltersilvacarneiro\Html\Src\Classes\Dao\GenericDao;
use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityDatabase;
use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityManagerException;
use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityState;

/**
 * This class is a crucial part of the application's data access layer,
 * responsible for managing and interacting with database entities through
 * their corresponding DAO (Data Access Object) classes. It provides methods
 * for initializing, deleting, and synchronizing the state of EntityDatabase
 * objects with the database.
 * 
 * @staticvar string _METHOD_GET_UNIQUE_ID      id
 * @staticvar string _METHOD_GET_REPRESENTATION representation of the class in DB
 * 
 * @method GenericDao|false _getDaoEntity(\ReflectionClass &$reflect)
 * @method array _getProperties(\ReflectionObject &$reflect, EntityDatabase &$entity)
 * @method EntityDatabase init(string $entityName, string|int $entityId)
 * @method bool del(EntityDatabase &$entity)
 * @method bool flush(EntityDatabase &$entity)
 * 
 * @category  EntityManager
 * @package   Josevaltersilvacarneiro\Html\Src\Classes\EntityManager
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.2.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Classes/EntityManager
 */
final class EntityManager
{
    private const _METHOD_GET_UNIQUE_ID      = "getUNIQUE";
    private const _METHOD_GET_REPRESENTATION = "getDatabaseRepresentation";

    /**
     * This method is used to find the appropriate GenericDao for a given entity
     * class. It relies on reflection attributes to determine the association
     * between the entity and its corresponding GenericDao.
     * 
     * @param \ReflectionClass $reflect A \ReflectionClass or \ReflectionObject
     * 
     * @return GenericDao|false GenericDao on success; false otherwise
     */
    private static function _getDaoEntity(
        \ReflectionClass $reflect
    ): GenericDao|false {
        $attrs = $reflect->getAttributes(
            flags: \ReflectionAttribute::IS_INSTANCEOF
        ); // array<\ReflectionAttribute>
        
        // retrieves the attributes defined for the given entity class

        foreach ($attrs as $attr) {

            $reflectDao = new \Reflectionclass($attr->getName());

            if ($reflectDao->isSubclassOf(GenericDao::class)) {
                return $reflectDao->newInstance();
            }

            // checks if the $reflectDao represents a subclass of GenericDao
        }

        return false;

        // if the entity doesn't have a dao attribute
        // return false
    }

    /**
     * This method plays a crucial role in the Entity Manager, allowing the
     * retrieval of property values from an EntityDatabase object using
     * reflection. By handling objects that implement the Entity interface, it
     * ensures consistency and flexibility in managing entity data during
     * runtime, facilitating data manipulation and synchronization tasks within
     * the application's data access layer.
     * 
     * @param \ReflectionClass $reflect A \ReflectionObject
     * @param EntityDatabase   $entity  An EntityDatabase object
     * 
     * @return array Key-value pairs of property names and values
     * @throws EntityManagerException
     */
    private static function _getProperties(\ReflectionObject $reflect,
        EntityDatabase $entity
    ): array {
        $propers    = $reflect->getProperties();    // array<\ReflectionProperty>

        $result     = array();
        foreach ($propers as $proper) {
            $name   = $proper->getName();           // gets the $proper name
            $value  = $proper->getValue($entity);   // gets the $proper's value

            if (is_object($value)) {
                $reflectProper  = new \ReflectionObject($value);

                if (!$reflectProper->implementsInterface(Entity::class)) {
                    $e = new EntityManagerException();

                    $e->setAdditionalMsg(
                        "The " . $proper->getName() .
                        " is a object but it doesn't implement " . Entity::class
                    );

                    throw $e;
                }

                // if the property value is an object, the method checks if it
                // implements the Entity interface. If not, it throws an
                // EntityManagerException with an additional message indicating
                // that the object should implement the Entity interface

                if ($reflectProper->isSubclassOf(EntityDatabase::class)) {

                    try { self::flush($value); 
                    } catch (EntityManagerException) {
                    }

                    // if the object is an instance of a subclass of EntityDatabase,
                    // the method calls the flush method to synchronize the object
                    // with the database. Any exceptions thrown by flush are ignored
                }

                if (!$reflectProper->hasMethod(self::_METHOD_GET_REPRESENTATION)) {
                    $e = new EntityManagerException();

                    $e->setAdditionalMsg(
                        "The " . $reflectProper->getName() .
                        " class doesn't have the "
                        . self::_METHOD_GET_REPRESENTATION . " method"
                    );

                    throw $e;

                    // If the method is not present, it throws an
                    // EntityManagerException with an additional message
                    // indicating that the method is missing
                }

                $reprMethod = $reflectProper->getMethod(
                    self::_METHOD_GET_REPRESENTATION
                );

                try { $value = $reprMethod->invoke($value); 
                }
                catch (\ReflectionException $e) {
                    throw new EntityManagerException($e);
                }
            }

            $result[$name] = $value;    // set the value to its respective property
        }

        return $result;
    }

    /**
     * This method provides a crucial mechanism for handling the initialization
     * and instantiation of EntityDatabase objects based on their names and IDs,
     * abstracting away the complexity of entity instantiation and ensuring
     * consistency with the underlying database structure.
     * 
     * @param string     $entityName Name of the subclass of EntityDatabase
     * @param string|int $entityId   Unique Identifier of the record
     * 
     * @return EntityDatabase New instance of a subclass
     * @throws EntityManagerException
     */
    public static function init(string $entityName,
        string|int $entityId
    ): EntityDatabase {
        try { $reflection = new \ReflectionClass($entityName); 
        }
        catch (\ReflectionException $e) { throw new EntityManagerException($e); 
        }

        // create a ReflectionClass for the specified entityName to
        // inspect its structure and attributes

        if (!$reflection->hasMethod(self::_METHOD_GET_UNIQUE_ID)) {
            $e = new EntityManagerException();

            $e->setAdditionalMsg(
                "The " . self::_METHOD_GET_UNIQUE_ID .
                " isn't defined in " . $entityName
            );

            throw $e;
        }

        // Check if the Entity has the required self::_METHOD_GET_UNIQUE_ID
        // method
        // ALL ENTITIES MUST IMPLEMENT THE self::_METHOD_GET_UNIQUE_ID
        // METHOD TO RETURN THE PRIMARY KEY FIELD

        $constructEntity    = $reflection->getConstructor();

        if ($constructEntity === false) {
            $e = new EntityManagerException();

            $e->setAdditionalMsg($entityName . " has no a __construct");

            throw $e;
        }

        // check if the entity has a valid constructor

        $dao                = self::_getDaoEntity($reflection);

        if ($dao === false) {
            $e = new EntityManagerException();

            $e->setAdditionalMsg(
                "Couldn't instantiate object dao from " .
                $entityName
            );

            throw $e;
        }

        // get the appropriate Dao class for the entity and instantiate it

        $methodEntity       = $reflection->getMethod(self::_METHOD_GET_UNIQUE_ID);

        $entity     = $dao->r(
            array($methodEntity->invoke(null, $entityId)   =>  $entityId)
        );

        // find the entity record in the database based on the provided entityId
        // using the Dao's read method

        if ($entity === false) {
            $e = new EntityManagerException();

            $e->setAdditionalMsg(
                "No record matching the " . $entityId .
                " was found"
            );

            throw $e;
        }

        $args = [];
        foreach ($constructEntity->getParameters() as $param) {

            if (!array_key_exists($param->getName(), $entity)) {

                if (!$param->allowsNull() && !$param->isOptional()) {
                    $e = new EntityManagerException();

                    $e->setAdditionalMsg(
                        "The " . $constructEntity->getShortName() .
                        " entity requires the " . $param->getName() . " param " .
                        "to be initialized and it cannot be null"
                    );

                    throw $e;
                }

                if ($param->allowsNull()) {
                    $args[] = null;
                }

                // if $param isn't defined in $entity, it isn't optional
                // and doesn't allow null, THERE WAS AN ERROR
            } elseif ($param->hasType() && $param->getType()->isBuiltin()) {
                settype(
                    $entity[$param->getName()],
                    (string) $param->getType()->getName()
                );

                $args[] = $entity[$param->getName()];

                // if $param is a builtin [ int, float, string, bool [
                // of php, set its type and add it to args
            } else {

                // here, $param in $entity isn't a builtin or it has no
                // type, then it must be instantiated as an object

                $properAttrs    = $param->getAttributes();

                // $properAttrs gets all attributes of
                // $param

                if (count($properAttrs) === 0) {
                    $e = new EntityManagerException();

                    $e->setAdditionalMsg(
                        "The " . $constructEntity->getShortName() .
                        " entity requires the " . $param->getName() . " param " .
                        "to be initialized but it doesn't specify the attribute"
                    );

                    throw $e;
                }

                // if the $param has no attributes,
                // then it cannot be instantiated
                // .*. THERE WAS AN IMPLEMENTATION ERROR .*.

                $properAttr     = $properAttrs[0];

                // by default, the attr used to instantiate a
                // property is the first

                try {
                    $reflectProper  = new \ReflectionClass($properAttr->getName());
                } catch (\ReflectionException $e) {
                    throw new EntityManagerException($e);
                }

                if ($reflectProper->isSubclassOf(EntityDatabase::class)) {
                    try {
                        $name   = $param->getName();
                        $args[] = empty($entity[$name]) ? null :
                            self::init($properAttr->getName(), $entity[$name]);
                    } catch (EntityManagerException $e) {
                        $ne = new EntityManagerException($e);

                        $ne->setAdditionalMsg(
                            "The " . $param->getName() .
                            " couldn't be instantiated using a recursive " .
                            "call to " . $properAttr->getName()
                        );

                        throw $ne;
                    }

                    // recursive call -> tries to instantiate the object
                    // that is an attribute of $entityName

                } elseif ($reflectProper->implementsInterface(Entity::class)) {

                    // if $reflectProper isn't a EntityDatabase
                    // then it must implement the Entity interface

                    $constructParam = $reflectProper->getConstructor();

                    // get the __construct of $reflectProper to make sure
                    // it has been implemented in the right way

                    if (is_null($constructParam) 
                        || $constructParam->getNumberOfParameters() === 0
                    ) {
                        $args[] = $reflectProper->newInstance();

                        continue;
                    } elseif ($constructParam->getNumberOfRequiredParameters() > 1) {
                        $e = new EntityManagerException();

                        $e->setAdditionalMsg(
                            "The " . $param->getName() .
                            " requires more than one parameter to be instantiated"
                        );

                        throw $e;
                    }

                    try {
                        $args[] = $reflectProper->newInstance(
                            $entity[$param->getName()]
                        );
                    } catch (\ReflectionException $e) {
                        throw new EntityManagerException($e);
                    }
                } else {
                    $e = new EntityManagerException();

                    $e->setAdditionalMsg(
                        "The " . $param->getName() .
                        " doesn't implement " . Entity::class
                    );

                    throw $e;

                    // an internal php or lib object was used as property
                }
            } // end of instantiating a Entity object
        } // end of foreach for each $proper

        if (count($args) < $constructEntity->getNumberOfRequiredParameters()) {
            $e = new EntityManagerException();

            $e->setAdditionalMsg($entityName . " is missing arguments");

            throw $e;
        }

        try {
            $entity = $reflection->newInstance(...$args);
            $entity->setSTATE(EntityState::PERSISTENT);
            return $entity;
        } catch (\InvalidArgumentException $e) {
            throw new EntityManagerException($e);
        }

        // instantiate the EntityDatabase object with the constructed $args array
        // and set its state to EntityState::PERSISTENT
    }

    /**
     * This method is responsible for deleting an EntityDatabase object from
     * the database.
     * 
     * @param EntityDatabase $entity Entity to be deleted
     * 
     * @return bool true on success; false otherwise
     */
    public static function del(EntityDatabase $entity): bool
    {
        if ($entity->getSTATE() !== EntityState::PERSISTENT) {
            return false;
        }

        // checks if the given $entity is in the PERSISTENT state. If not,
        // it returns false because only entities in the PERSISTENT state
        // can be deleted

        $reflect    = new \ReflectionObject($entity);

        $dao        = self::_getDaoEntity($reflect);

        if ($dao === false) {
            return false;
        }

        // if the DAO cannot be instantiated, it returns false

        return $dao->d(
            array(
            $entity::getIDNAME()    =>  $entity->getID()
            )
        );
    }

    /**
     * This method is responsible for synchronizing the state of an
     * EntityDatabase object with the corresponding database record.
     * 
     * @param EntityDatabase $entity Entity to be synchronized
     * 
     * @return bool true on success; false otherwise
     */
    public static function flush(EntityDatabase $entity): bool
    {
        if ($entity->getSTATE() === EntityState::PERSISTENT 
            || $entity->getSTATE() === EntityState::REMOVED
        ) {
            return false;
        }

        // if the state is PERSISTENT or REMOVED, there is no need to create or
        // update; so it returns false

        $reflect = new \ReflectionObject($entity);

        try {
            $props = self::_getProperties($reflect, $entity); 
        } catch (EntityManagerException) {
            return false; 
        }

        // GETS THE PROPERTIES AND THEIR VALUES FROM THE $entity
        // if an EntityManagerException is caught during the property retrieval,
        // it returns false, indicating the synchronization failure

        $dao = self::_getDaoEntity($reflect);   // a class that extends GenericDao

        if ($dao === false) {
            return false;
        }

        // if the DAO cannot be instantiated, it returns false

        switch ($entity->getSTATE()) {
        case EntityState::TRANSIENT:    // IF created but not stored,
            $id = $dao->ic($props);     // store it in the DB

            if ($id === false) {
                return false;
            }

            $entity->setID($id);
            break;
        case EntityState::DETACHED:     // IF changed after being read,
            $ok = $dao->u($props);      // update it in the DB

            if (!$ok) {
                return false;
            }

            break;
        }

        $entity->setSTATE(EntityState::PERSISTENT);

        // on success, change the state to persistent

        return true;
    }
}
