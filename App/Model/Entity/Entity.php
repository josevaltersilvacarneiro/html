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

use Josevaltersilvacarneiro\Html\Src\Traits\TraitProperty;

abstract class Entity
{
	use TraitProperty;

	public function __construct()
	{}

	public abstract static function getIDNAME(): string;

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
	 * @version		0.1
	 * @access		public
	 * @copyright	Copyright (C) 2023, José V S Carneiro
 	 * @license		GPLv3
	 */

	public abstract static function getUNIQUE(mixed $uID): string;
}
