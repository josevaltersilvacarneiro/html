<?php

declare(strict_types=1);

/**
 * Log is a comprehensive PHP package designed to facilitate
 * efficient and reliable logging. It offers a robust set of
 * features and intuitive APIs to simplify the process of
 * recording, managing, and analyzing logs, ensuring better
 * visibility into your application's behavior and performance.
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
 * @package     Josevaltersilvacarneiro\Html\Src\Classes\Log
 */

namespace Josevaltersilvacarneiro\Html\Src\Classes\Log;

use Josevaltersilvacarneiro\Html\Src\Classes\Log\Log;

/**
 * This class is a specialization of Log. It
 * responsible for storing the logs of the
 * type PDO.
 * 
 * @author		José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version		0.1
 * @see			Josevaltersilvacarneiro\Html\Src\Classes\Log\Log
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license		GPLv3
 */

class PDOLog extends Log
{
    public function __construct()
    {
        parent::__construct("PDO.log");
    }

    public function store(string $message): void
    {
        parent::store("/PDO/ " . $message);
    }
}
