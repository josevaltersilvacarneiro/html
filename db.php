<?php

declare(strict_types=1);

/**
 * This script executes the database migrations.
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
 */

$dir = __DIR__ . '/Migrations/';
$files = array_diff(scandir($dir), ['..', '.']);
sort($files);

$filename = end($files);

try {
    $pdo = new \PDO('mysql:host=127.0.0.1;dbname=database_html', 'root', 'admin');
    $contents = file_get_contents($dir . $filename);
    if ($contents === false) {
        throw new \Exception('Error opening file');
    }
    $pdo->exec($contents);
} catch (\PDOException $e) {
    echo 'Error connecting to database' . PHP_EOL;
    echo $e->getMessage();
} catch (\Exception $e) {
    echo 'Error opening file' . PHP_EOL;
    echo $e->getMessage();
}
