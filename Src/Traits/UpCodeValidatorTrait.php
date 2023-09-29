<?php

declare(strict_types=1);

/**
 * This package is responsible for offering useful functions.
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
 * @category Traits
 * @package  Josevaltersilvacarneiro\Html\Src\Traits
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Traits
 */

namespace Josevaltersilvacarneiro\Html\Src\Traits;

/**
 * This trait offers functions for validating registration codes.
 * 
 * @category  UpCodeValidatorTrait
 * @package   Josevaltersilvacarneiro\Html\Src\Traits
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.4
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Traits
 */
trait UpCodeValidatorTrait
{
    /**
     * Checks if a registration hash code is valid.
     * 
     * @param string $hashCode Hash code to be checked
     * 
     * @return bool True if so; false otherwise
     */
    public static function isHashCodeValid(string $hashCode): bool
    {
        return strlen($hashCode) === 64 &&
            preg_match("/^[a-f0-9]{64}$/", $hashCode);
    }

    /**
     * Generates a hash for a given word.
     * 
     * @param string $word Word to be hashed
     * 
     * @return string Hashed word
     */
    private static function _generateCodeHash(string $word): string
    {
        return hash('SHA256', $word);
    }

    /**
     * Generates a random code.
     * 
     * @return string Random code
     */
    private static function _generateValidCode(): string
    {
        try {
            return random_bytes(16);
        } catch (\Random\RandomException) {
            // Warning: This is not a secure random number generator
            return '5f3e2a1b4c3d2e1f';
        }
    }
}
