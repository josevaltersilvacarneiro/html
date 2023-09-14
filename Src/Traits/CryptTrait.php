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
 * This trait offers functions for encrypting and decrypting data.
 * 
 * @category  CryptTrait
 * @package   Josevaltersilvacarneiro\Html\Src\Traits
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Traits
 */
trait CryptTrait
{
    private const ALGOCRYPT = 'AES-256-CBC';

    /**
     * Encrypts a string.
     * 
     * @param string $data The string to be encrypted
     * @param string $key  The key to be used in the encryption
     * 
     * @return string|false The encrypted string or false if an error occurs
     */
    private static function _encrypt(string $data, string $key): string|false
    {
        $ivSize = openssl_cipher_iv_length(self::ALGOCRYPT);

        if ($ivSize === false) {
            return false;
        }

        try {
            $iv = openssl_random_pseudo_bytes($ivSize);
        } catch (\Exception) {
            return false;
        }

        $encryptedData = openssl_encrypt(
            $data, self::ALGOCRYPT, $key,
            OPENSSL_RAW_DATA, $iv
        );

        if ($encryptedData === false) {
            return false;
        }

        return base64_encode($iv . $encryptedData);
    }

    /**
     * Decrypts a string.
     * 
     * @param string $encryptedData The string to be decrypted
     * @param string $key           The key to be used in the decryption
     * 
     * @return string|false The decrypted string or false if an error occurs
     */
    private static function _decrypt(
        string $encryptedData, string $key
    ): string|false {
        $encryptedData = base64_decode($encryptedData);

        if ($encryptedData === false) {
            return false;
        }

        $ivSize = openssl_cipher_iv_length(self::ALGOCRYPT);

        if ($ivSize === false) {
            return false;
        }

        $iv = substr($encryptedData, 0, $ivSize);
        $encryptedData = substr($encryptedData, $ivSize);

        return openssl_decrypt(
            $encryptedData, self::ALGOCRYPT, $key,
            OPENSSL_RAW_DATA, $iv
        );
    }
}
