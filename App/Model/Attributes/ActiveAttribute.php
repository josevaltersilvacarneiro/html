<?php

declare(strict_types=1);

/**
 * This package conatins the attributes of the entities.
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
 * @category Attributes
 * @package  Josevaltersilvacarneiro\Html\App\Model\Attributes
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/App/Model/Attributes
 */

namespace Josevaltersilvacarneiro\Html\App\Model\Attributes;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\AttributeInterface;

/**
 * This class represents a active attribute or status.
 * 
 * @category  ActiveAttribute
 * @package   Josevaltersilvacarneiro\Html\App\Model\Attributes
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.3
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Model/Attributes
 */
class ActiveAttribute implements AttributeInterface
{
    /**
     * Initializes the attribute.
     * 
     * @param bool $_active The active
     */
    public function __construct(private bool $_active = true)
    {
    }

    /**
     * This method disables the entity that has this attribute.
     * 
     * @return static itself
     */
    public function disable(): static
    {
        $this->_active = false;
        return $this;
    }

    /**
     * This method enables the entity that has this attribute.
     * 
     * @return static itself
     */
    public function enable(): static
    {
        $this->_active = true;
        return $this;
    }

    /**
     * This method returns the representation of the attribute.
     * 
     * @return mixed Status: true or false
     */
    public function getRepresentation(): mixed
    {
        return $this->_active;
    }

    /**
     * This method returns a new instance of this class.
     * 
     * @param bool $value The active
     * 
     * @return static|null The new instance
     */
    public static function newInstance(mixed $value): ?static
    {
        return new static((bool) $value);
    }
}
