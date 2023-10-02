<?php

/**
 * This package is responsible for rendering pages.
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
 * @package  Josevaltersilvacarneiro\Html\Src\Classes\Render
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Classes/Render
 */

namespace Josevaltersilvacarneiro\Html\Src\Classes\Render;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Render\HtmlRenderInterface;

use Twig\Environment;
use \Twig\Loader\FilesystemLoader;

/**
 * This class is specific to render HTML pages.
 * 
 * @var string $headerTitle       page title
 * @var string $headerDescription page description
 * @var string $keywords          page keywords
 * @var string $robots            page robots [ All, Index, Follow, NoIndex, NoFollow, None, NoArchive [
 * 
 * @method static setDir(string $dir)                       sets up the directory where the pages are stored
 * @method static setTitle(string $title)                   sets up the head title
 * @method static setDescription(string $description)       sets up the head description
 * @method static setKeywords(string $keywords)             sets up the head keywords
 * @method static setRobots(string $robots)                 sets up the head robots
 * 
 * @category  HTMLRender
 * @package   Josevaltersilvacarneiro\Html\Src\Classes\Render
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.10.4
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Classes/Render
 */
abstract class HTMLRender implements HtmlRenderInterface
{
	private const PATH = _TEMPLATES;

	private string $_page;
	private string $_title;
	private string $_description;
	private string $_keywords;
	private string $_robots = "Index";
	private array $_variables = [];

	/**
	 * Sets up the page that should be rendered.
	 * 
	 * @param string $page Page
	 * 
	 * @return static itself
	 */
	public function setPage(string $page): static
	{
		$this->_page = $page;

		return $this;
	}

	/**
	 * Sets up the head title.
	 * 
	 * @param string $title The title of the page
	 * 
	 * @return static itself
	 */
	public function setTitle(string $title): static
	{
		$this->_title = $title;

		return $this;
	}

	/**
	 * Sets up the head description.
	 * 
	 * @param string $description The description of the page
	 * 
	 * @return static itself
	 */
	public function setDescription(string $description): static
	{
		$this->_description = $description;

		return $this;
	}

	/**
	 * Sets up the head page theme keywords.
	 * 
	 * @param string $keywords Keywords
	 * 
	 * @return static itself
	 */
	public function setKeywords(string $keywords): static
	{
		$this->_keywords = $keywords;

		return $this;
	}

	/**
	 * Sets up the head robots.
	 * 
	 * @param string $robots Robots
	 * 
	 * @return static itself
	 */
	public function setRobots(string $robots): static
	{
		$this->_robots = $robots;

		return $this;
	}

	/**
	 * Sets up the page variables.
	 * 
	 * @param array $variables Variables
	 * 
	 * @return static itself
	 */
	public function setVariables(array $variables): static
	{
		$this->_variables = $variables;

		return $this;
	}

	/**
	 * This method reders the layout and returns it.
	 * 
	 * @return string What is rendered for the user
	 */
	public function renderLayout(): string  
    {
		$loader = new FilesystemLoader(self::PATH);
		$twig   = new Environment($loader);

		$glbs = [
			'PAGE_'        => $this->_page,
			'TITLE_'       => $this->_title,
			'DESCRIPTION_' => $this->_description,
			'KEYWORDS_'    => $this->_keywords,
			'AUTHOR_'      => __AUTHOR__,
			'URL_'         => __URL__,
			'ROBOTS_'      => $this->_robots,
			'CSS_'         => __CSS__,
			'VERSION_'     => __VERSION__,
			'JS_'          => __JS__,
			'IMG_'         => __IMG__
		];

		$vars = array_merge(
			$glbs,
			$this->_variables,
		);

		return $twig->render($this->_page . '.html.twig', $vars);
	}
}
