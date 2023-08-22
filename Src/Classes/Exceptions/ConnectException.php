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

namespace Josevaltersilvacarneiro\Html\Src\Classes\Sql;

use Josevaltersilvacarneiro\Html\Src\Classes\Log\SqlLog;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Exceptions\ConnectExceptionInterface;

/**
 * In Runtime, it may be impossible to connect the database. This exception
 * must be thrown when this happens.
 * 
 * @category  ConnectException
 * @package   Josevaltersilvacarneiro\Html\Src\Classes\Exceptions
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://www.php.net/manual/en/class.runtimeexception.php
 */
final class ConnectException extends \RuntimeException implements
    ConnectExceptionInterface
{
    /**
     * Initializes the exception.
     * 
     * @param \Throwable|null $previous If this exception is a relaunch.
     */
    public function __construct(\Throwable|null $previous = null)
    {
        parent::__construct(
            'Error connecting to the database',
            ConnectExceptionInterface::SQL_ERROR_CODE, $previous
        );
    }

    /**
     * This method saves the error in a log system.
     * 
     * @return void
     */
    public function storeLog(): void
    {
        $log = new SqlLog();
        $log->setFilename($this->getFile());
        $log->setLine($this->getLine());
        $log->setMessage($this->getMessage());
        $log->save();
    }
}
