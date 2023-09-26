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
 * This interface represents the HtmlRender of the page.
 * 
 * @category  HtmlRenderInterface
 * @package   Josevaltersilvacarneiro\Html\Src\Interfaces\Render
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.1.0
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Render
 */
interface HtmlRenderInterface extends RenderInterface
{
    /**
     * Sets up the directory where the pages are stored.
     * 
     * @param string $dir Directory
     * 
     * @return static itself
     */
    public function setDir(string $dir): static;

    /**
     * Sets up the page that should be rendered.
     * 
     * @param string $page Page
     * 
     * @return static itself
     */
    public function setPage(string $page): static;

    /**
     * Sets up the head title.
     * 
     * @param string $title The title of the page
     * 
     * @return static itself
     */
    public function setTitle(string $title): static;

    /**
     * Sets up the head description.
     * 
     * @param string $description The description of the page
     * 
     * @return static itself
     */
    public function setDescription(string $description): static;

    /**
     * Sets up the head page theme keywords.
     * 
     * @param string $keywords Keywords
     * 
     * @return static itself
     */
    public function setKeywords(string $keywords): static;

    /**
     * Sets up the head robots.
     * 
     * @param string $robots Robots
     * 
     * @return static itself
     */
    public function setRobots(string $robots): static;

    /**
     * Sets up the page variables.
     * 
     * Note that this method must be called by the controller
     * to pass the variables to the view based on the business
     * model.
     * 
     * @param array<string,mixed> $variables Variables
     * 
     * @return static itself
     */
    public function setVariables(array $variables): static;
}
