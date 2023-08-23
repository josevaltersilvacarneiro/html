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
 * @category Exceptions
 * @package  Josevaltersilvacarneiro\Html\Src\Interfaces\Exceptions
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Exceptions
 */

namespace Josevaltersilvacarneiro\Html\Src\Interfaces\Exceptions;

/**
 * All exceptions of this application must implement this interface.
 * 
 * @category  HtmlExceptionInterface
 * @package   Josevaltersilvacarneiro\Html\Src\Interfaces\Exceptions
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Exceptions
 */
interface HtmlExceptionInterface extends \Throwable
{
    public const APP_ERROR_CODE = 1;
    public const APP_WARNING_CODE = 2;
    public const SQL_ERROR_CODE = 10;
    public const SQL_WARNING_CODE = 20;
    public const FRAMEWORK_ERROR_CODE = 100;
    public const FRAMEWORK_WARNING_CODE = 200; 

    /**
     * This method stores the log of the exception.
     * Tell, Don't Ask principle.
     * 
     * @return void No return
     * 
     * @see https://martinfowler.com/bliki/TellDontAsk.html
     */
    public function storeLog(): void;
}
