<?php

declare(strict_types=1);

/**
 * Log is a comprehensive PHP package designed to facilitate efficient and
 * reliable logging. It offers a robust set of features and intuitive APIs
 * to simplify the process of recording and managing logs, ensuring better
 * visibility into your application's behavior and performance.
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
 * @category Log
 * @package  Josevaltersilvacarneiro\Html\Src\Classes\Log
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  https://www.gnu.org/licenses/quick-guide-gplv3.html GPLv3
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Classes/Log
 */

namespace Josevaltersilvacarneiro\Html\Src\Classes\Log;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Log\LogInterface;

/**
 * This class is responsible for storing the logs.
 * 
 * @category  AppLog
 * @package   Josevaltersilvacarneiro\Html\Src\Classes\Log
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.2
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Classes/Log
 */
class Log implements LogInterface
{
    private const FILENAME = __ROOT__ . '/Logs/';
    private string $_filename = '/example/foo/bar.log';
    private int $_line = 0;
    private string $_message = 'It saves without setting the message';

    /**
     * Initializes the log object.
     * 
     * @param string $logFilename The name of the log file
     */
    public function __construct(private readonly string $logFilename)
    {
    }

    /**
     * Sets the filename where the error occurred.
     * 
     * @param string $filename Filename
     * 
     * @return static Itself
     */
    public function setFilename(string $filename): static
    {
        $this->_filename = $filename;

        return $this;
    }

    /**
     * Sets the line where the error occurred.
     * 
     * @param int $line Line
     * 
     * @return static Itself
     */
    public function setLine(int $line): static
    {
        $this->_line = $line;

        return $this;
    }

    /**
     * Sets the message to be saved.
     * 
     * @param string $message Message
     * 
     * @return static Itself
     */
    public function setMessage(string $message): static
    {
        $this->_message = $message;

        return $this;
    }

    /**
     * Saves the log. It use "tell, don't ask" principle.
     * 
     * @return void
     */
    public function save(): void
    {
        $date = new \DateTimeImmutable();
        $date->setTimezone(new \DateTimeZone(_TIMEZONE_));

        $message = <<<MESSAGE
        {$date->format('Y-m-d H:i:s')} - IN "{$this->_filename}" on LINE {$this->_line}:~# {$this->_message}
        MESSAGE;

        file_put_contents(
            self::FILENAME . $this->logFilename,
            $message,
            FILE_APPEND
        );
    }
}
