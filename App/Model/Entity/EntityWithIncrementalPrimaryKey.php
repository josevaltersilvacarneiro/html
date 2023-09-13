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

use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\
    EntityWithIncrementalPrimaryKeyInterface;
use Josevaltersilvacarneiro\Html\App\Model\Entity\Entity;

/**
 * This class represents a entity with an incremental primary key.
 * 
 * @method void setId(string|int $id) Sets the id
 * 
 * @category  EntityWithIncrementalPrimaryKey
 * @package   Josevaltersilvacarneiro\Html\App\Model\Entity
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Model/Entity
 */
abstract class EntityWithIncrementalPrimaryKey extends Entity implements
    EntityWithIncrementalPrimaryKeyInterface
{
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
     */
    public function setId(string|int $id): void
    {
        if ($this->getCallingClass() !== EntityManager::class) {
            return ;
        }

        // only the EntityManager class can modify the
        // ID of an entity

        try {
            $myself = new \ReflectionObject($this);
            $proper = $myself->getProperty($this->getIdName());
            $proper->setValue($this, $id);
        } catch (\ReflectionException) {
        }    // ignore failures
    }
}
