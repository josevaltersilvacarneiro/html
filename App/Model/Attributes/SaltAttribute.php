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

use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\SaltAttributeInterface;
use Josevaltersilvacarneiro\Html\Src\Classes\Exceptions\AttributeException;

/**
 * This class represents a salt, whose role is to protect
 * from rainbow table attacks.
 * 
 * @category  SaltAttribute
 * @package   Josevaltersilvacarneiro\Html\App\Model\Attributes
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Model/Attributes
 */
class SaltAttribute implements SaltAttributeInterface
{
    /**
     * Initializes the attribute.
     * 
     * @param string $_salt The salt
     * 
     * @throws AttributeException If the salt is not valid
     */
    public function __construct(private string $_salt)
    {
        if (mb_strlen($this->_salt) < 10) {
            throw new AttributeException(
                "The value '{$this->_salt}' is not a valid salt."
            );
        }
    }

    /**
     * This method returns the representation of this attribute.
     * 
     * @return mixed Salt
     */
    public function getRepresentation(): mixed
    {
        return $this->_salt;
    }

    /**
     * This method returns a new instance of this class.
     * 
     * @param mixed $value Salt
     * 
     * @return static|null $this on success; null on failure
     */
    public function newInstance(string $value): ?static
    {
        try {
            return new SaltAttribute($value);
        } catch (AttributeException) {
            return null;
        }
    }
}
