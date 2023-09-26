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
 * @category Log
 * @package  Josevaltersilvacarneiro\Html\Src\Interfaces\Log
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Log
 */

namespace Josevaltersilvacarneiro\Html\Src\Interfaces\Log;

/**
 * This interface represents a log class.
 * 
 * Note that this interface needs to handler errors
 * without throwing exceptions: Tell, don't ask principle.
 * 
 * @category  LogInterface
 * @package   Josevaltersilvacarneiro\Html\Src\Interfaces\Log
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.2
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Log
 * @see       https://martinfowler.com/bliki/TellDontAsk.html
 */
interface LogInterface
{
    /**
     * Sets the filename where the error occurred.
     * 
     * @param string $filename Where the error occurred
     * 
     * @return static itself
     */
    public function setFilename(string $filename): static;

    /**
     * Sets the line where the error occurred.
     * 
     * @param int $line Where the error occurred
     * 
     * @return static itself
     */
    public function setLine(int $line): static;

    /**
     * Sets the error message.
     * 
     * @param string $message Error message
     * 
     * @return static itself
     */
    public function setMessage(string $message): static;

    /**
     * This procedure is responsible for saving the log.
     * 
     * @return void
     */
    public function save(): void;
}
