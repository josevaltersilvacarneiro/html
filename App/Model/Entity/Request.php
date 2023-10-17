<?php

declare(strict_types=1);

/**
 * The Entity package contains classes that represent the database
 * tables as entities. These entity classes encapsulate the structure
 * and behavior of specific tables, providing a convenient way to
 * interact with the corresponding database records.
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
 * @category Entity
 * @package  Josevaltersilvacarneiro\Html\App\Model\Entity
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/App/Model/Entity
 */

namespace Josevaltersilvacarneiro\Html\App\Model\Entity;

use Josevaltersilvacarneiro\Html\App\Model\Entity\EntityWithIncrementalPrimaryKey;
use Josevaltersilvacarneiro\Html\App\Model\Dao\RequestDao;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\RequestEntityInterface;

use Josevaltersilvacarneiro\Html\App\Model\Attributes\{
    IncrementalPrimaryKeyAttribute};
use Josevaltersilvacarneiro\Html\App\Model\Attributes\IpAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\PortAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\DateAttribute;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\IpAttributeInterface;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Attributes\PortAttributeInterface;

/**
 * The Request Entity represents a request. It contains properties and methods
 * to manage request-related data and operations.
 * 
 * @var ?IncrementalPrimaryKeyAttribute $_id     primary key
 * @var IpAttribute                     $_ip     ip @example {192.168.1.56, ::1}
 * @var PortAttribute                   $_port   port @example {5632}
 * @var DateAttribute                   $_access date object of last access
 * 
 * @category  Request
 * @package   Josevaltersilvacarneiro\Html\App\Model\Entity
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.4.2
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Model/Entity
 */
#[RequestDao]
final class Request extends EntityWithIncrementalPrimaryKey implements
    RequestEntityInterface
{
    /**
     * Initializes the Request object.
     * 
     * If any of the validation checks fail, a \InvalidArgumentException is
     * thrown with a specific error message corresponding to the validation
     * failure.
     * 
     * @param ?IncrementalPrimaryKeyAttribute $_id     Primary key
     * @param IpAttribute                     $_ip     IP
     * @param PortAttribute                   $_port   Port
     * @param DateAttribute                   $_access Date of last access
     * 
     * @return void
     * @throws \InvalidArgumentException If any of the validation checks fail
     */
    public function __construct(
        #[IncrementalPrimaryKeyAttribute('request_id')]
        private ?IncrementalPrimaryKeyAttribute $_id,
        #[IpAttribute('ip')] private IpAttribute $_ip,
        #[PortAttribute('port')] private PortAttribute $_port,
        #[DateAttribute('access_date')] private DateAttribute $_access
    ) {
        if ($_access > new DateAttribute()) {
            throw new \InvalidArgumentException("Invalid Date");
        }
    }

    /**
     * Returns the name of the primary key.
     * 
     * @return string Primary key name
     */
    public static function getIdName(): string
    {
        return '_id';
    }

    /**
     * Returns the request's IP.
     * 
     * @return IpAttributeInterface IP
     */
    public function getIp(): IpAttributeInterface
    {
        return clone $this->_ip;
    }

    /**
     * Returns the request's port.
     * 
     * @return PortAttributeInterface Port
     */
    public function getPort(): PortAttributeInterface
    {
        return clone $this->_port;
    }

    /**
     * Returns the request's date of last access.
     * 
     * @return DateAttribute Date
     */
    public function getDate(): DateAttribute
    {
        return clone $this->_access;
    }

    /**
     * This method checks if the request can be deleted based on LGPD regulations
     * by ensuring that the request is more than one year old. If the request is
     * less than one year old, the method prevents deletion and returns false.
     * Otherwise, it proceeds with the deletion operation and returns the result
     * of the deletion attempt.
     * 
     * @return bool true on success; false otherwise
     */
    public function killme(): bool
    {
        $todayDate = new DateAttribute();
        $interval  = \DateInterval::createFromDateString("1 year");

        if ($this->getDate()->add($interval) > $todayDate) {
            return false;
        }

        // if, when adding one year to the date of the request,
        // it's greater than today's date, then the request was
        // made less than one year ago. This means that it cannot
        // be deleted because of the LGPD

        return parent::killme();
    }
}
