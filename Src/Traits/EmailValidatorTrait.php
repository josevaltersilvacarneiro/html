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

use Josevaltersilvacarneiro\Html\Src\Traits\UpCodeValidatorTrait;

/**
 * This trait offers functions for validating registration codes.
 * 
 * @category  EmailValidatorTrait
 * @package   Josevaltersilvacarneiro\Html\Src\Traits
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.2
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Traits
 */
trait EmailValidatorTrait
{
    use UpCodeValidatorTrait;

    private const _PASSWORD = 'password';

    /**
     * Generates a code for a given email.
     * 
     * @return string Code @example '5f3e2a1b4c3d2e1f'
     */
    private static function _generateEmailCode(): string
    {
        return self::_generateValidCode();
    }

    /**
     * Generates a hash for a given email and code.
     * 
     * @param string $email Email to generate code
     * @param string $code  Generated Code
     * 
     * @return string md5 hash @example '87f0b9a302bc9019746f81de717e8e66'
     */
    private static function _generateEmailCodeHash(
        string $email,
        string $code
    ): string {
        return self::_generateCodeHash($code . $email . date('H'));
    }

    /**
     * Confirms if a given code is valid for a given email based on
     * a given hash.
     * 
     * @param string $email         Email to generate code
     * @param string $code          Generated Code
     * @param string $emailCodeHash Hash to be checked
     * 
     * @return bool true on success; false otherwise
     */
    private static function _isCodeHashValid(
        string $email,
        string $code,
        string $emailCodeHash
    ): bool {
        return self::_generateEmailCodeHash($email, $code) === $emailCodeHash;
    }
}
