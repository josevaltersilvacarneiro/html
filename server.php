<?php

declare(strict_types=1);

/**
 * A simple server for web testing.
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
 * @category Server
 * @package  Josevaltersilvacarneiro\Html\
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  https://www.gnu.org/licenses/quick-guide-gplv3.html GPLv3
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/
 */

include_once 'Src/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$descriptors = array(
    0 => array("pipe", "r"), // stdin
    1 => array("pipe", "w"), // stdout
    2 => array("pipe", "w"), // stderr
);

$process = proc_open('php --server 0.0.0.0:8080 --docroot Public/', $descriptors, $pipes, null, $_ENV);

if (is_resource($process)) {
    fclose($pipes[0]); // Close stdin

    // Read the output from the subprocess (stdout and stderr)
    $output = stream_get_contents($pipes[1]);
    $errorOutput = stream_get_contents($pipes[2]);

    fclose($pipes[1]);
    fclose($pipes[2]);

    // Wait for the process to finish
    $exitCode = proc_close($process);

    echo "Exit code: $exitCode\n";
    echo "Output:\n$output\n";
    echo "Error Output:\n$errorOutput\n";
}
