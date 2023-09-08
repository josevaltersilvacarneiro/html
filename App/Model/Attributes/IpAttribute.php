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

use Josevaltersilvacarneiro\Html\Src\Classes\Exceptions\AttributeException;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\IpAttributeInterface;

/**
 * This class represents a IP address.
 * 
 * @category  IpAttribute
 * @package   Josevaltersilvacarneiro\Html\App\Model\Attributes
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Model/Attributes
 */
class IpAttribute implements IpAttributeInterface
{
    /**
     * Initializes the attribute.
     * 
     * @param string $ip The IP address
     * 
     * @throws AttributeException If the IP address is not valid
     */
    public function __construct(private string $ip)
    {
        if (!filter_var($this->ip, FILTER_VALIDATE_IP)) {
            throw new AttributeException(
                "The value '{$this->ip}' is not a valid IP address."
            );
        }
    }

    /**
     * This method returns the representation of this attribute.
     * 
     * @return mixed Ip address
     */
    public function getRepresentation(): mixed
    {
        return $this->ip;
    }

    /**
     * This method returns a new instance of this class.
     * 
     * @param mixed $value Ip address
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
