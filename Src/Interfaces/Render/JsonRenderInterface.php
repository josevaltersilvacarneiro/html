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
 * @category Render
 * @package  Josevaltersilvacarneiro\Html\Src\Interfaces\Render
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Render
 */

namespace Josevaltersilvacarneiro\Html\Src\Interfaces\Render;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Render\RenderInterface;

/**
 * This interface represents the JsonRender.
 * 
 * @category  JsonRenderInterface
 * @package   Josevaltersilvacarneiro\Html\Src\Interfaces\Render
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Render
 */
interface JsonRenderInterface extends RenderInterface
{
    /**
     * Sets up the status of the response based on
     * the business model.
     * 
     * @param int $status Status code
     * 
     * @return static itself
     */
    public function setStatus(int $status): static;

    /**
     * Sets up the expiration, that is, how long is this
     * response valid. `0` means that it doesn't expire.
     * 
     * @param int $expiration Expiration time
     * 
     * @return static itself
     */
    public function setExpiration(int $expiration = 0): static;

    /**
     * Sets up the data to be sent.
     * 
     * @param array $data Data
     * 
     * @return static itself
     */
    public function setData(array $data = []): static;
}
