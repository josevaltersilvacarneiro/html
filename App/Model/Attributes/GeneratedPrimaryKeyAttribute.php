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
    GeneratedPrimaryKeyAttributeInterface};
use Josevaltersilvacarneiro\Html\Src\Classes\Exceptions\AttributeException;

/**
 * This class represents a Primary Key that is not
 * generated automatically.
 * 
 * @category  GeneratedPrimaryKeyAttribute
 * @package   Josevaltersilvacarneiro\Html\App\Model\Attributes
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.9
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Model/Attributes
 */
final class GeneratedPrimaryKeyAttribute extends PrimaryKeyAttribute implements
    GeneratedPrimaryKeyAttributeInterface
{
    /**
     * Initializes the attribute.
     * 
     * @param string $_generatedPrimaryKey The primary key
     * 
     * @throws AttributeException If the primary key is not valid
     */
    public function __construct(?string $_generatedPrimaryKey = null)
    {
        $this->_id = $_generatedPrimaryKey ?? self::generatePrimaryKey();

        if (preg_match('/^[a-f0-9]{64}$/', $this->_id) === false) {
            throw new AttributeException(
                "The '{$this->_id}' is not a valid primary key",
            );
        }
    }

    /**
     * This method returns a value that can be used as a primary key.
     * 
     * @return string SHA256 hash
     * @throws AttributeException If is not possible to generate a primary key
     */
    public static function generatePrimaryKey(): string
    {
        try {
            $bytes = random_bytes(32);
            return hash('SHA256', bin2hex($bytes));
        } catch (\Random\RandomException | \ValueError $e) {
            throw new AttributeException(
                'It was not possible to generate a primary key',
                $e,
            );
        }
    }
}
