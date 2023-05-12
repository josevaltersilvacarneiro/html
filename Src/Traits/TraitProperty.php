<?php

/**
 * This package is responsible for offering
 * useful functions.
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
 * @package     Src\Traits
 */

namespace Josevaltersilvacarneiro\Html\Src\Traits;

/**
 * This trait offers functions that handle
 * attributes as properties.
 *
 * @author	José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version	0.1
 * @see		https://www.php.net/manual/en/language.oop5.overloading.php#object.set
 * @see		https://www.php.net/manual/en/language.oop5.overloading.php#object.get
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license	GPLv3
 */

trait TraitProperty
{
	public function __set(string $attributeName, mixed $value): void
	{
		$method = 'set' . ucfirst($attributeName);

		$this->$method($value);
	}

	public function __get(string $attributeName): mixed
	{
		$method = 'get' . ucfirst($attributeName);

		return $this->$method();
	}
}
