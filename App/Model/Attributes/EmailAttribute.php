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

use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\EmailAttributeInterface;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\UniqueAttributeInterface;

use Josevaltersilvacarneiro\Html\Src\Classes\Exceptions\AttributeException;

/**
 * This class represents a email.
 * 
 * @category  EmailAttribute
 * @package   Josevaltersilvacarneiro\Html\App\Model\Attributes
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Model/Attributes
 */
class EmailAttribute implements EmailAttributeInterface, UniqueAttributeInterface
{
    private string $_username;
    private string $_domain;
    private array $_subdomains;

    /**
     * Initializes the attribute.
     * 
     * @param string $email The email address
     */
    public function __construct(private string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new AttributeException(
                'The email address is not valid'
            );
        }

        $parts = explode('@', $email);
        $this->_username   = $parts[0];
        $this->_domain     = $parts[1];
        $this->_subdomains = explode('.', $this->_domain);
    }

    /**
     * This method is responsible for returning the username.
     * 
     * @return string Username @example "git"
     */
    public function getUsername(): string
    {
        return $this->_username;
    }

    /**
     * This method is responsible for returning the domain.
     * 
     * @return string Domain @example "josevaltersilvacarneiro.net"
     */
    public function getDomain(): string
    {
        return $this->_domain;
    }

    /**
     * This method is responsible for returning the subdomains.
     * 
     * @return array<string> Subdomains @example ["foo", "bar"]
     */
    public function getSubdomains(): array
    {
        return $this->_subdomains;
    }

    /**
     * This method returns the representation of the attribute.
     * 
     * @return mixed Email @example git@foo.bar.net
     */
    public function getRepresentation(): mixed
    {
        return $this->email;
    }

    /**
     * This method returns a new instance of this class.
     * 
     * @param mixed $value Email @example git@foo.bar.net
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
}
