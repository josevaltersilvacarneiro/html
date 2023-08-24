<?php

declare(strict_types=1);

/**
 * Based on the project requirements, the interfaces will be written.
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
 * @category Entities
 * @package  Josevaltersilvacarneiro\Html\Src\Interfaces\Entities
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Entities
 */

namespace Josevaltersilvacarneiro\Html\Src\Interfaces\Entities;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\EntityInterface;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\IpAttributeInterface;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\PortAttributeInterface;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\DateAttributeInterface;

/**
 * This interface represents a http request.
 * 
 * @category  RequestEntityInterface
 * @package   Josevaltersilvacarneiro\Html\Src\Interfaces\Entities
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Entities
 */
interface RequestEntityInterface extends EntityInterface
{
    /**
     * This method returns the IP address of the request.
     * 
     * Note that the ip address cannot be modified after it has been set.
     * Analyze the legislation of your country.
     * 
     * @return IpAttributeInterface The IP address of the request
     */
    public function getIp(): IpAttributeInterface;

    /**
     * This method returns the port of the request.
     * 
     * Note that the port cannot be modified after it has been set.
     * 
     * @return PortAttributeInterface The port of the request
     */
    public function getPort(): PortAttributeInterface;

    /**
     * This method returns the date of the request.
     * 
     * Note that the date cannot be modified after it has been set.
     * 
     * @return DateAttributeInterface The date of the request
     */
    public function getDate(): DateAttributeInterface;
}
