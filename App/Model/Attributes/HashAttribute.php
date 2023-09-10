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

use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\
    HashAttributeInterface;
use Josevaltersilvacarneiro\Html\Src\Classes\Exceptions\AttributeException;

/**
 * This class represents a hash.
 * 
 * @category  HashAttribute
 * @package   Josevaltersilvacarneiro\Html\App\Model\Attributes
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Model/Attributes
 */
final class HashAttribute implements HashAttributeInterface
{
    private const HASH_ALGO = PASSWORD_DEFAULT;

    /**
     * Initializes the attribute.
     * 
     * @param string $_hash The hash or the value to be hashed
     * 
     * @throws AttributeException If the hash is not valid
     */
    public function __construct(private string $_hash)
    {
        if (!$this->_isHashValid($this->_hash)) {
            $hash = password_hash($this->_hash, self::HASH_ALGO);

            if ($hash === false) {
                throw new AttributeException(
                    'It wasn\'t possible to hash the value'
                );
            }

            $this->_hash = $hash;
        }
    }

    /**
     * Sets the hash.
     * 
     * @param string $value The value to be hashed or a hash
     * 
     * @return static itself
     * @throws AttributeException If the $value is not valid
     */
    public function setHash(string $value): static
    {
        if (!$this->_isHashValid($value)) {
            $value = password_hash($value, self::HASH_ALGO);

            if ($value === false) {
                throw new AttributeException(
                    'It wasn\'t possible to hash the value'
                );
            }
        }

        $this->_hash = $value;
        return $this;
    }

    /**
     * This method returns if $value is the string that
     * generated the hash or if it is the hash itself.
     * 
     * @param string $value The value to be hashed or a hash
     * 
     * @return bool true if yes; false otherwise
     */
    public function isThisYou(string $value): bool
    {
        return password_verify($value, $this->_hash) || $this->_hash === $value;
    }

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
    ): bool {
        return $hash1->getRepresentation() === $hash2->getRepresentation();
    }

    /**
     * This method returns the representation of this attribute.
     * 
     * @return mixed The hash
     */
    public function getRepresentation(): mixed
    {
        return $this->_hash;
    }

    /**
     * This method returns a new instance of this class.
     * 
     * @param mixed $value The value to be hashed or a hash
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
     * This method returns if the $hash matches the algorithm.
     * 
     * @param string $hash The hash
     * 
     * @return bool true if yes; false otherwise
     */
    private function _isHashValid(string $hash): bool
    {
        $hashInfo = password_get_info($hash);

        return $hashInfo['algoName'] !== 'unknown';
    }
}
