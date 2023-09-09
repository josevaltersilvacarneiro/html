<?php

declare(strict_types=1);

/**
 * Based on the project requirements, the interfaces will be written.
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
 * @category Attributes
 * @package  Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Attributes
 */

namespace Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\AttributeInterface;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Exceptions\
    AttributeExceptionInterface;

/**
 * This interface must be implemented by the classes that represent
 * the user's password.
 * 
 * @category  HashAttributeInterface
 * @package   Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.2
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Attributes
 */
interface HashAttributeInterface extends AttributeInterface
{
    /**
     * This method rehashes based on $value.
     * 
     * @param string $value The value to be hashed or a hash
     * 
     * @return static itself
     * @throws AttributeExceptionInterface If the $value is not valid
     */
    public function setHash(string $value): static;

    /**
     * This method returns if $value is able to build it.
     * 
     * @param string $value The value to be hashed or a hash
     * 
     * @return bool true if passing $value to newInstance() returns itself; false
     *  otherwise
     */
    public function isThisYou(string $value): bool;

    /**
     * This method returns if the hashes are equal, that is,
     * if they were generated from the same value.
     * 
     * @param HashAttributeInterface $hash1 The first hash
     * @param HashAttributeInterface $hash2 The second hash
     * 
     * @return bool true if the hashes are equal; false otherwise
     */
    public static function areHashesEqual(
        HashAttributeInterface $hash1,
        HashAttributeInterface $hash2
    ): bool;
}
