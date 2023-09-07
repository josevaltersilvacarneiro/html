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

use Josevaltersilvacarneiro\Html\Src\Classes\Log\Log;

/**
 * This class is a specialization of Log. It responsible for
 * storing the logs of the type Attribute.
 * 
 * @category  AttributeLog
 * @package   Josevaltersilvacarneiro\Html\Src\Classes\Log
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Classes/Log
 */
final class AttributeLog extends Log
{
    /**
     * Initializes the log.
     */
    public function __construct()
    {
        parent::__construct('attribute.log');
    }
}
