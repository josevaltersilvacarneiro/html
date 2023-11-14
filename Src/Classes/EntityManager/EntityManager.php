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

use Josevaltersilvacarneiro\Html\App\Model\Attributes\IncrementalPrimaryKeyAttribute;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\PrimaryKeyAttributeInterface;
use Josevaltersilvacarneiro\Html\App\Model\Entity\Entity;
use Josevaltersilvacarneiro\Html\Src\Classes\Dao\GenericDao;
use Josevaltersilvacarneiro\Html\Src\Classes\Exceptions\EntityException;
use Josevaltersilvacarneiro\Html\Src\Classes\Exceptions\EntityManagerException;
use Josevaltersilvacarneiro\Html\Src\Enums\EntityState;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\AttributeInterface;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\UniqueAttributeInterface;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\EntityInterface;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\{
    EntityWithIncrementalPrimaryKeyInterface};

/**
 * This class is a crucial part of the application's data access layer,
 * responsible for managing and interacting with database entities through
 * their corresponding DAO (Data Access Object) classes. It provides methods
 * for initializing, deleting, and synchronizing the state of EntityDatabase
 * objects with the database.
 * 
 * @staticvar string _METHOD_GET_UNIQUE_NAME  Method name to get the unique name
 * @staticvar string _METHOD_ATTR_NEWINSTANCE Method name to create a new instance
 * 
 * @method EntityDatabase init(string $entityName, string|int $entityId)
 * @method bool flush(EntityDatabase &$entity)
 * @method bool del(EntityDatabase &$entity)
 * 
 * @method GenericDao|false _getDaoEntity(\ReflectionClass $reflect)
 * @method array _getProperties(\ReflectionObject $reflect, EntityDatabase $entity)
 * @method array _processParameters(\ReflectionClass $reflect, \ReflectionMethod $construct, array $entity)
 * 
 * @category  EntityManager
 * @package   Josevaltersilvacarneiro\Html\Src\Classes\EntityManager
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.4.4
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Classes/EntityManager
 */
final class EntityManager
{
    private const _METHOD_GET_UNIQUE_NAME         = 'getUniqueName';
    private const _METHOD_ATTR_NEWINSTANCE        = 'newInstance';

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
     * This method maps the names of the attributes of an Entity
     * reflection object to the names of the corresponding
     * fields in the database.
     * 
     * @param \ReflectionClass $reflect A \ReflectionClass of Entity
     * 
     * @return array<string,string>   Key-value pairs of attribute names
     * @throws EntityManagerException If any errors occur during the process
     */
    private static function _getMappedProperties(\ReflectionClass $reflect): array
    {
        $map = [];
        try {
            $method = $reflect->getMethod('__construct');
            $params = $method->getParameters();

            foreach ($params as $param) {
                $attrs = $param->getAttributes();
                $attr  = $attrs[0];
                $name  = $attr->getArguments()[0];

                $map[$param->getName()] = $name;
            }
        } catch (\ReflectionException | \OutOfRangeException $e) {
            throw new EntityManagerException(
                'Wasn\'t possible to map the attribute names',
                $e
            );
        }

        return $map;
    }

    /**
     * This method plays a crucial role in the EntityManager, allowing the
     * retrieval of property values from an Entity object using reflection.
     * 
     * @param \ReflectionClass $reflect A \ReflectionObject
     * @param EntityInterface  $entity  An Entity object
     * 
     * @return array<string,mixed>    Key-value pairs of property names and values
     * @throws EntityManagerException If any errors occur during the process
     */
    private static function _getProperties(\ReflectionObject $reflect,
        EntityInterface $entity
    ): array {

        try {
            $map = self::_getMappedProperties($reflect);
        } catch (EntityManagerException $e) {
            throw new EntityManagerException(
                'Unable to map', $e
            );
        }

        $propers = $reflect->getProperties();

        $result = [];
        foreach ($propers as $proper) {
            $name  = $map[$proper->getName()];
            $value = $proper->getValue($entity);

            if (is_object($value)) {
                if ($value instanceof EntityInterface) {
                    self::flush($value);
                    $value = $value->getId()?->getRepresentation();
                } else {
                    $value = $value->getRepresentation();
                }
            }

            $result[$name] = $value;
        }

        return $result;
    }

    /**
     * Instantiates a primary key attribute object based on the
     * $key using reflection parameter.
     * 
     * @param \ReflectionClass $reflection \ReflectionClass of Entity
     * @param string|int       $key        Unique identifier
     * 
     * @return ?PrimaryKeyAttributeInterface New instance of a subclass on success; null otherwise
     */
     private static function _instantiatePrimaryKey(\ReflectionClass $reflection, string|int $key): ?PrimaryKeyAttributeInterface
     {
        try {
            $params = $reflection->getConstructor()->getParameters();

            foreach ($params as $param) {
                $attr = $param->getAttributes()[0];
                $attrReflect = new \ReflectionClass($attr->getName());
                if ($attrReflect->isSubclassOf(PrimaryKeyAttributeInterface::class)) {
                    $method = $attrReflect->getMethod(self::_METHOD_ATTR_NEWINSTANCE);
                    return $method->invoke(null, $key);
                }
            }
        } catch (\ReflectionException) {
            return null;
        }

        return null;
     }

    /**
     * This method provides a crucial mechanism for handling the initialization
     * and instantiation of Entity objects based on their names and ids,
     * abstracting away the complexity of entity instantiation and ensuring
     * consistency with the database structure.
     * 
     * @param string     $entityName Entity's name
     * @param string|int $entityId   Unique Identifier of the record
     * 
     * @return EntityInterface        New instance of a subclass
     * @throws EntityManagerException If any errors occur during the process
     */
    public static function init(string $entityName,
        UniqueAttributeInterface $id
    ): EntityInterface {

        try {
            $reflection = new \ReflectionClass($entityName);
        } catch (\ReflectionException $e) {
            throw new EntityManagerException(
                'Unable to instantiate ' . $entityName . ' class',
                $e
            );
        }

        // create a ReflectionClass for the specified entityName to
        // inspect its structure and attributes

        $constructEntity = $reflection->getConstructor();

        if (is_null($constructEntity)) {
            throw new EntityManagerException(
                'The ' . $entityName .
                ' class doesn\'t have a __construct'
            );
        }

        $dao = self::_getDaoEntity($reflection);

        if ($dao === false) {
            throw new EntityManagerException(
                'Couldn\'t instantiate object dao from ' . $entityName,
            );
        }

        try {
            $methodEntity = $reflection->getMethod(self::_METHOD_GET_UNIQUE_NAME);
            $unique = self::_getMappedProperties($reflection)[$methodEntity->invoke(null, $id)];

            $entity = $dao->r(
                [$unique => $id->getRepresentation()]
            );
    
            // find the entity record in the database based on the provided entityId
            // using the Dao's read method
        } catch (\ReflectionException $e) {
            throw new EntityManagerException(
                'The ' . self::_METHOD_GET_UNIQUE_NAME .
                ' method is not defined in ' . $entityName,
                $e
            );
        }

        if ($entity === false) {
            throw new EntityManagerException(
                'No record matching the ' . $id->getRepresentation() . ' was found'
            );
        }

        try {
            $args = self::_processParameters($reflection, $constructEntity, $entity);
        } catch (EntityManagerException $e) {
            throw new EntityManagerException(
                'Unable to process the parameters',
                $e
            );
        }

        if (count($args) < $constructEntity->getNumberOfRequiredParameters()) {

            throw new EntityManagerException(
                $entityName . ' is missing arguments'
            );
        }

        try {
            $entity = $reflection->newInstance(...$args);
            if ($entity instanceof EntityInterface) {
                $entity->setState(EntityState::PERSISTENT);
                return $entity;
            }

            throw new EntityManagerException(
                'The ' . $entityName . ' class doesn\'t implement EntityInterface'
            );
        } catch (\InvalidArgumentException $e) {
            throw new EntityManagerException(
                'Couldn\'t instantiate ' . $entityName . ' class',
                $e
            );
        }

        // instantiate the Entity object with the constructed $args array
        // and set its state to EntityState::PERSISTENT
    }

    /**
     * This method is responsible for deleting an Entity object from
     * the database.
     * 
     * @param EntityInterface $entity Entity to be deleted
     * 
     * @return bool true on success; false otherwise
     */
    public static function del(EntityInterface $entity): bool
    {
        if ($entity->getState() !== EntityState::PERSISTENT) {
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

        try {
            return $dao->d(
                array(
                    $entity::getIdName() => $entity->getId()->getRepresentation(),
                )
            );
        } catch (EntityException) {
            return false;
        }
    }

    /**
     * This method is responsible for synchronizing the state of an
     * EntityDatabase object with the corresponding database record.
     * 
     * @param EntityInterface $entity Entity to be synchronized
     * 
     * @return bool true on success; false otherwise
     */
    public static function flush(EntityInterface $entity): bool
    {
        if ($entity->getState() === EntityState::PERSISTENT 
            || $entity->getState() === EntityState::REMOVED
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

        switch ($entity->getState()) {
            case EntityState::TRANSIENT:    // IF created but not stored,
                $id = $dao->ic($props);     // store it in the DB

                if ($id === false) {
                    return false;
                }

                if ($entity instanceof EntityWithIncrementalPrimaryKeyInterface) {
                    $entity->setId(IncrementalPrimaryKeyAttribute::newInstance($id));
                }
                break;
            case EntityState::DETACHED:     // IF changed after being read,
                $ok = $dao->u($props);      // update it in the DB

                if (!$ok) {
                    return false;
                }

                break;
        }

        $entity->setState(EntityState::PERSISTENT);

        // on success, change the state to persistent

        return true;
    }

    /**
     * This method is used to instantiate the params of the Entity.
     * 
     * @param \ReflectionClass $reflect A \ReflectionClass of Entity
     * @param \ReflectionMethod $construct A \ReflectionMethod of __construct
     * @param array<string,mixed>  $entity Key-value pairs of the fields and values on db
     * 
     * @return array<mixed> Arguments to initialize the Entity
     * @throws EntityManagerException If any errors occur during the process
     */
    private static function _processParameters(\ReflectionClass $reflect, \ReflectionMethod $construct, array $entity): array
    {
        try {
            $map = self::_getMappedProperties($reflect);
        } catch (EntityManagerException $e) {
            throw new EntityManagerException(previous: $e);
        }

        $args = [];
        foreach ($construct->getParameters() as $param) {
            $name = $map[$param->getName()];

            if (!array_key_exists($name, $entity)) {

                if (!$param->allowsNull() && !$param->isOptional()) {

                    throw new EntityManagerException(
                        'The ' . $construct->getShortName() .
                        ' is mapped to ' . $name .
                        ', but the table doesn\'t have this field'
                    );
                }

                if ($param->allowsNull()) {
                    $args[] = null;
                }
            } elseif ($param->hasType() && $param->getType()->isBuiltin()) {

                // https://www.php.net/manual/en/class.reflectionnamedtype.php

                settype(
                    $entity[$name],
                    (string) $param->getType()->getName()
                );

                $args[] = $entity[$name];

                // if $param is a builtin [ int, float, string, bool [
                // of php, set its type and add it to args
            } else {

                try {
                    $attrs         = $param->getAttributes();
                    $attr          = $attrs[0];
                    $reflectProper = new \ReflectionClass($attr->getName());
                } catch (\ReflectionException $e) {
                    throw new EntityManagerException(
                        'Unable to create a instance of reflection class on line ' .
                        __LINE__, $e
                    );
                }

                if ($reflectProper->isSubclassOf(Entity::class)) {

                    try {
                        $args[] = empty($entity[$name]) ? null :
                            self::init($attr->getName(), self::_instantiatePrimaryKey($reflect, $entity[$name]));
                    } catch (EntityManagerException $e) {
                        throw new EntityManagerException(
                            $attr->getName() .
                            'Couldn\'t be instantiated using a recursive call',
                            $e
                        );
                    }

                } elseif ($reflectProper->implementsInterface(
                    AttributeInterface::class
                )
                ) {

                    try {
                        $method = $reflectProper->getMethod(
                            self::_METHOD_ATTR_NEWINSTANCE
                        );
                        $args[] = $method->invoke(null, $entity[$name]);
                    } catch (\ReflectionException $e) {
                        throw new EntityManagerException(
                            'Unable to invoke ' . self::_METHOD_ATTR_NEWINSTANCE,
                            $e
                        );
                    }
                }
            } // end of instantiating a Entity object
        }

        return $args;
    }
}
