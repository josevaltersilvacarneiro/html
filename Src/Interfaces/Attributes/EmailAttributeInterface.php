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

namespace Josevaltersilvacarneiro\Html\Src\Interfaces;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\AttributeInterface;

/**
 * This interface represents a email.
 * 
 * Attention: if the email is also a UNIQUE CONSTRAINT, the class that
 * implements it must implement the UniqueConstraintInterface.
 * 
 * @category  EmailAttributeInterface
 * @package   Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Attributes
 */
interface EmailAttributeInterface extends AttributeInterface
{
    /**
     * This method is responsible for returning the username.
     * For example, if the email is "git@josevaltersilvacarneiro.net",
     * it returns "git".
     * 
     * @return string Username @example "git"
     */
    public function getUsername(): string;

    /**
     * This method is responsible for returning the domain.
     * For example, if the email is "git@josevaltersilvacarneiro.net",
     * it returns "josevaltersilvacarneiro.net".
     * 
     * @return string Domain @example "josevaltersilvacarneiro.net"
     */
    public function getDomain(): string;

    /**
     * This method is responsible for returning the subdomains.
     * For example, if the email is "git@foo.bar.josevaltersilvacarneiro.net",
     * it returns ["foo", "bar"].
     * 
     * Note that the subdomains are returned from the most specific
     * to the least specific, i.e. in the same order they appear.
     * 
     * @return array<string> Subdomains @example ["foo", "bar"]
     */
    public function getSubdomains(): array;
}
