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

use Josevaltersilvacarneiro\Html\Src\Interfaces\Exceptions\AppExceptionInterface;
use Josevaltersilvacarneiro\Html\Src\Classes\Log\AppLog;

/**
 * This exception is thrown when the request cannot be answered
 * properly.
 * 
 * @category  AppException
 * @package   Josevaltersilvacarneiro\Html\Src\Classes\Exceptions
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Classes/Exceptions
 */
class AppException extends \DomainException implements AppExceptionInterface
{
    /**
     * Initializes the exception.
     * 
     * @param string     $message  The Exception message to throw
     * @param \Exception $previous Used for the exception chaining
     */
    public function __construct(string $message, \Exception $previous = null)
    {
        parent::__construct(
            "# App Exception -> " . $message,
            AppExceptionInterface::APP_ERROR_CODE, $previous
        );
    }

    /**
     * Store the log.
     * 
     * @return void
     */
    public function storeLog(): void
    {
        $appLog = new AppLog();
        $appLog
            ->setFilename($this->getFile())
            ->setLine($this->getLine())
            ->setMessage($this->getMessage())
            ->save();
    }
}
