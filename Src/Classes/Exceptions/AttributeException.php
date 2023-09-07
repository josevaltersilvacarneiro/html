<?php

declare(strict_types=1);

/**
 * This package is responsible for accessing the database.
 * PHP VERSION 8.2.0
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
 * @category Exceptions
 * @package  Josevaltersilvacarneiro\Html\Src\Classes\Exceptions
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  https://www.gnu.org/licenses/quick-guide-gplv3.html GPLv3
 * @link     http://www.gnu.org/licenses/
 */

namespace Josevaltersilvacarneiro\Html\Src\Classes\Exceptions;

use Josevaltersilvacarneiro\Html\Src\Classes\Log\AttributeLog;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Exceptions\
    AttributeExceptionInterface;

/**
 * If an invalid argument is passed at initialization or when
 * setting a property, this exception must be thrown.
 * 
 * @category  AttributeException
 * @package   Josevaltersilvacarneiro\Html\Src\Classes\Exceptions
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://www.php.net/manual/en/class.invalidargumentexception.php
 */
final class AttributeException extends \InvalidArgumentException implements
    AttributeExceptionInterface
{
    /**
     * Initializes the exception.
     * 
     * @param string|null     $message  [optional] The Exception message to throw
     * @param \Throwable|null $previous [optional] Used for the exception chaining
     */
    public function __construct(
        string|null $message = null,
        \Throwable|null $previous = null
    ) {
        parent::__construct(
            $message ?? 'Error in attribute',
            AttributeExceptionInterface::APP_ERROR_CODE, $previous
        );
    }

    /**
     * This method is responsible for storing the log.
     * 
     * @return void
     */
    public function storeLog(): void
    {
        $attributeLog = new AttributeLog();
        $attributeLog->setFilename($this->getFile());
        $attributeLog->setLine($this->getLine());
        $attributeLog->setMessage($this->getMessage());
        $attributeLog->save();
    }
}
