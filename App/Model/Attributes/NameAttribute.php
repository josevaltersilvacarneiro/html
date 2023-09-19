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

use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\{
    NameAttributeInterface};
use Josevaltersilvacarneiro\Html\Src\Classes\Exceptions\AttributeException;

/**
 * This class represents a Fullname.
 * 
 * @category  NameAttribute
 * @package   Josevaltersilvacarneiro\Html\App\Model\Attributes
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.3
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Model/Attributes
 */
final class NameAttribute implements NameAttributeInterface
{
    /**
     * Initializes the attribute.
     * 
     * @param string $_completeName The complete name
     * 
     * @throws AttributeException If the name is not valid
     */
    public function __construct(private string $_completeName)
    {
        if (!$this->_isNameValid($_completeName)) {
            throw new AttributeException(
                "The value '{$_completeName}' is not a valid name."
            );
        }
    }

    /**
     * Sets the complete name.
     * 
     * @param string $completeName The complete name
     * 
     * @return static itself
     * @throws AttributeException If the name is not valid
     */
    public function setCompleteName(string $completeName): static
    {
        if (!$this->_isNameValid($completeName)) {
            throw new AttributeException(
                "The value '{$completeName}' is not a valid name."
            );
        }

        $this->_completeName = mb_convert_case($completeName, MB_CASE_LOWER);

        return $this;
    }

    /**
     * This method returns the first name.
     * 
     * @return string The first name @example José
     */
    public function getFirstName(): string
    {
        return $this->_getFormattedName(explode(" ", $this->_completeName)[0]);
    }

    /**
     * This method returns the last name.
     * 
     * @return string The last name @example Carneiro
     */
    public function getLastName(): string
    {
        $name = explode(" ", $this->_completeName);
        return $this->_getFormattedName(end($name));
    }

    /**
     * This method returns the representation of this attribute.
     * 
     * @return mixed The complete name
     */
    public function getRepresentation(): mixed
    {
        return $this->_completeName;
    }

    /**
     * This method returns a new instance of this class.
     * 
     * @param mixed $value The complete name
     * 
     * @return static|null $this on success, null on failure
     */
    public static function newInstance(mixed $value): ?static
    {
        try {
            return new static($value);
        } catch (AttributeException) {
            return null;
        }
    }

    /**
     * Informs if the name is valid.
     * 
     * @param string $completeName The complete name
     * 
     * @return bool true if the name is valid, false otherwise
     */
    private function _isNameValid(string $completeName): bool
    {
        return !mb_strlen($completeName) > 80 &&
            preg_match("/^.{3,} .*.{3,}$/", $completeName);
    }

    /**
     * This method returns the formatted name.
     * 
     * @param string $name The name
     * 
     * @return string The formatted name
     */
    private function _getFormattedName(string $name): string
    {
        $case = mb_strlen($name) > 2 ? MB_CASE_UPPER : MB_CASE_LOWER;
        return mb_convert_case($name, $case);
    }
}
