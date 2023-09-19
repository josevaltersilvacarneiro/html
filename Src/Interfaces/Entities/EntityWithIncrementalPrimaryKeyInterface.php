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

use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\EntityInterface;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\{
    IncrementalPrimaryKeyAttributeInterface};

/**
 * All entities that have an incremental primary key must implement this interface
 * for EntityManager sets its id automatically.
 * 
 * @category  EntityWithIncrementalPrimaryKeyInterface
 * @package   Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\EntityInterface
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.4
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Entities
 */
interface EntityWithIncrementalPrimaryKeyInterface extends EntityInterface
{
    /**
     * This method sets the primary key.
     * 
     * @return IncrementalPrimaryKeyAttributeInterface The primary key
     */
    public function getId(): IncrementalPrimaryKeyAttributeInterface;
}
