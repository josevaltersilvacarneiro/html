<?php

/**
 * This package is responsible for rendering
 * pages that are in __VIEW__.
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
 * @package     Src\Classes\Render
 */

namespace Src\Classes\Render;

use Src\Classes\Render\RenderInterface;
use Src\Traits\{TraitProperty, TraitImport};

/**
 * This class contains methods that render the web
 * pages. However, it must be extended by the                                    
 * renderer of the specific page type.
 *
 * @var 	string  $dir            	directory in __VIEW__
 *
 * @property-read string $dir
 *
 * @method      void    setDir(string $dir)     sets up the Controller directory
 * @method      string  getDir()                returns the Controller directory
 * @method      void    renderLayout()          renders the main layout
 *
 * @author      José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version     0.2
 * @abstract
 * @copyright   Copyright (C) 2023, José V S Carneiro
 * @license     GPLv3
 */

abstract class Render implements RenderInterface
{
	use TraitProperty;
	use TraitImport;

	private string $dir;

	public function setDir(string $dir): void
        {
                $this->dir = $dir;
        }

	public function getDir(): string
        {
                return $this->dir;
	}

	public function renderLayout(): void   
        {
                $this->import(self::PATH . 'Layout.php');
        }
}
