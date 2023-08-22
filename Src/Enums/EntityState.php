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

namespace Josevaltersilvacarneiro\Html\Src\Enums;

/**
 * The EntityState enum represents different states that an entity can have
 * in the context of the application's data persistence.
 * 
 * TRANSIENT: The TRANSIENT state indicates that an entity has been created
 * but has not been synchronized with the database yet. It typically
 * represents a newly created entity that has not been persisted.
 * 
 * PERSISTENT: The PERSISTENT state represents an entity that has been
 * synchronized with the database. It indicates that the entity has been read
 * from the database or updated and reflects the state of the corresponding
 * database record.
 * 
 * DETACHED: The DETACHED state represents an entity that is no longer
 * synchronized with the database. It typically occurs when an entity has been
 * removed from its persistence context or when a separate instance of the
 * entity is used. The detached entity may still hold data from a previous
 * synchronization but cannot directly update or persist changes to the
 * database.
 * 
 * REMOVED: The REMOVED state indicates that an entity has been removed from
 * the database but has not been dereferenced by the garbage collector yet. It
 * represents a logically deleted entity that is marked for removal but still
 * exists in memory.
 *
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.1
 * @see			https://www.php.net/manual/en/language.enumerations.overview.php
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */

enum EntityState
{
	case TRANSIENT;     # created but not synchronized with the database
    case PERSISTENT;    # synchronized      - for example, it was read or updated
    case DETACHED;      # not synchronized  - for example, sets used
    case REMOVED;       # removed but not dereferenced by the garbage collector
}
