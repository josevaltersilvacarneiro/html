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
 * @package	Josevaltersilvacarneiro\Html\Src\Classes\Render
 */

namespace Josevaltersilvacarneiro\Html\Src\Classes\Render;

use Josevaltersilvacarneiro\Html\Src\Classes\Render\Render;

/**
 * This class is specific to render HTML pages.
 *
 * @var		string	$headerTitle		page title
 * @var		string	$headerDescription	page description
 * @var		string	$keywords		page keywords
 *
 * @method	void	setTitle(string $title)				sets up the head title
 * @method	void	setDescription(string $headerDescription)	sets up the head title
 * @method	void	setKeywords(string $keywords)			sets up the head keywords
 * @method	void	setRobots(string $robots)			sets up the head robots
 * @method	string	getTitle()					returns the head title
 * @method	string	getDescription()				returns the head description
 * @method	string	getKeywords()					returns the head keywords
 * @method	string	getRobots()					returns the head robots
 *
 * @method	string getCSS(string $element)				ret.s the css file according to the html element
 * @method	string getCSSHeader()					ret.s the css file for the element header
 * @method	string getCSSMain()					ret.s the css file for the element main
 * @method	string getCSSFooter()					ret.s the css file for the element footer
 *
 * @method	void	addHeader()					renders the page header
 * @method	void	addMain()					renders the page main
 * @method	void	addFooter()					renders the page footer
 *
 * @author	José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version	0.4
 * @abstract
 * @see		Josevaltersilvacarneiro\Html\App\Controller\HTMLController
 * @copyright	Copyright (C) 2023, José V S Carneiro
 * @license	GPLv3
 */

abstract class HTMLRender extends Render
{
	private const CSS_PATH = __ROOT__ . 'Public' . DIRECTORY_SEPARATOR .
		'Css' . DIRECTORY_SEPARATOR;

	private string $headerTitle;
	private string $headerDescription;
	private string $keywords;
	private string $robots = "index";

	protected function setTitle(string $headerTitle): void
	{
		$this->headerTitle = $headerTitle;
	}

	protected function setDescription(string $headerDescription): void
	{
		$this->headerDescription = $headerDescription;
	}

	protected function setKeywords(string $keywords): void
	{
		$this->keywords = $keywords;
	}

	protected function setRobots(string $robots): void
	{
		$this->robots = $robots;
	}

	protected function getTitle(): string
	{
		return $this->headerTitle;
	}

	protected function getDescription(): string
	{
		return $this->headerDescription;
	}

	protected function getKeywords(): string
	{
		return $this->keywords;
	}

	protected function getRobots(): string
	{
		return $this->robots;
	}

	/**
	 * This function returns the full path to the
	 * css file according to the html $element
	 * passed as parameter.
	 *
	 * @param	string	$element	html element [Header|Main|Footer]
	 *
	 * @author	José V S Carneiro <git@josevaltersilvacarneiro.net>
	 * @version	0.4.1
	 * @access	private
	 */
	
	private function getCSS(string $element): string
        {
                $filename       = $this->getDir() . $element . "-" . __VERSION__ . ".css";
                
                $otherFilename  = __CSS__ . $element . "-" . __VERSION__ . ".css";

                return $this->fexists(self::CSS_PATH . $filename) ? __CSS__ . $filename : $otherFilename;
        }

        protected function getCSSHeader(): string
        {
                return $this->getCSS("Header");
        }
         
        protected function getCSSMain(): string
        {
                return $this->getCSS("Main");
        }

        protected function getCSSFooter(): string
        {
		return $this->getCSS("Footer");
	}

	protected function addHeader(): void
	{
		$this->importPage(self::PATH, $this->getDir(), "Header-" . __VERSION__ . ".php");
	}

	protected function addMain(): void
	{
		$this->importPage(self::PATH, $this->getDir(), "Main-"   . __VERSION__ . ".php");
	}

	protected function addFooter(): void
	{
		$this->importPage(self::PATH, $this->getDir(), "Footer-" . __VERSION__ . ".php");
	}
}
