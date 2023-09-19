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

use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\{
    PrimaryKeyAttributeInterface};
use Josevaltersilvacarneiro\Html\Src\Interfaces\Exceptions\{
    AttributeExceptionInterface};

/**
 * This interface represents a primary key that not
 * increment automatically.
 * 
 * @category  GeneratedPrimaryKeyAttributeInterface
 * @package   Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.4
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Attributes
 */
interface GeneratedPrimaryKeyAttributeInterface extends PrimaryKeyAttributeInterface
{
    /**
     * This method returns a value that can be used as a primary key.
     * For example, a SHA-256 hash.
     * 
     * @return string A value that can be used as a primary key
     * 
     * @throws AttributeExceptionInterface If isn't possible to generate a pk
     */
    public static function generatePrimaryKey(): string;
}
