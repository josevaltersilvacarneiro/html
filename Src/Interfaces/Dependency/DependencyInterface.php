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
 * @category Dependency
 * @package  Josevaltersilvacarneiro\Html\Src\Interfaces\Dependency
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Dependency
 */

namespace Josevaltersilvacarneiro\Html\Src\Interfaces\Dependency;

/**
 * All classes that can be used as a dependency must implement this interface.
 * 
 * @category  DependencyInterface
 * @package   Josevaltersilvacarneiro\Html\Src\Interfaces\Dependency
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Dependency
 */
interface DependencyInterface
{
    /**
     * Returns an instance of itself.
     * 
     * @param array<DependencyInterface> $dependencies Its dependencies
     * 
     * @return static itself on success, false on failure
     */
    public static function fork(array $dependencies = []): static|false;
}
