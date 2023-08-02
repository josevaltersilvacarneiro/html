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

use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityException;

/**
 * This class extends the EntityException class to handle exceptions specific to
 * the EntityManager. It provides the ability to set and retrieve additional
 * information or messages associated with the exception. This allows for more
 * specific error handling and reporting in the context of the EntityManager
 * operations.
 *
 * @var string $additionalMsg Additional information about the error
 *
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.1
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */

final class EntityManagerException extends EntityException
{
    # this additional message should only used in the test scenarios
    private string $additionalMsg = "";

	public function __construct(\Exception $previous = NULL)
    {
        parent::__construct("The EntityManager found an inconsistency",
            $previous);
    }

    public function setAdditionalMsg(string $msg): void
    {
        $this->additionalMsg = $msg;
    }

    public function getAdditionalMsg(): string
    {
        return $this->additionalMsg;
    }
}
