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

/**
 * This class provides a way to handle and throw specific exceptions related to
 * entity-related errors. It extends the base \Exception class and allows for
 * custom error messages and error codes. By using this class, it's easy to
 * differentiate and catch exceptions specific to entity operations and handle
 * them accordingly.
 *
 * @staticvar int ERROR_CODE  the error code
 *
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.1
 * @see			https://www.php.net/manual/en/class.exception.php
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */

class EntityException extends \Exception
{
    private const ERROR_CODE = 12;

	public function __construct(string $message, \Exception $previous = NULL)
    {
        parent::__construct("# Entity Exception -> " . $message, self::ERROR_CODE,
            $previous);
    }
}
