<?php

/**
 * This package is responsible for rendering pages.
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

use Josevaltersilvacarneiro\Html\Src\Interfaces\Render\RenderInterface;

use Twig\Environment;
use \Twig\Loader\FilesystemLoader;

/**
 * This class is specific to render HTML pages.
 * 
 * @var		string	$headerTitle		page title
 * @var		string	$headerDescription	page description
 * @var		string	$keywords			page keywords
 * @var		string	$robots				page robots [ All, Index, Follow, NoIndex, NoFollow, None, NoArchive [
 * 
 * @method	void	setTitle(string $title)						sets up the head title
 * @method	void	setDescription(string $headerDescription)	sets up the head title
 * @method	void	setKeywords(string $keywords)				sets up the head keywords
 * @method	void	setRobots(string $robots)					sets up the head robots
 * 
 * @method	string	getTitle()				returns the head title
 * @method	string	getDescription()		returns the head description
 * @method	string	getKeywords()			returns the head keywords
 * @method	string	getRobots()				returns the head robots
 * 
 * @category  HTMLRender
 * @package   Josevaltersilvacarneiro\Html\Src\Classes\Render
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.9.0
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Classes/Render
 */
abstract class HTMLRender implements RenderInterface
{
	private const PATH = __VIEW__;

	private string $_dir;
	private string $headerTitle;
	private string $headerDescription;
	private string $keywords;
	private string $robots = "Index";

	/**
	 * Sets up the View directory.
	 * 
	 * @param string $dir directory in __VIEW__
	 * 
	 * @return static itself
	 */
	public function setDir(string $dir): static
	{
		$this->_dir = $dir . DIRECTORY_SEPARATOR;

		return $this;
	}

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

	/**
	 * Returns the View directory.
	 * 
	 * @return string directory in __VIEW__
	 */
	public function getDir(): string
    {
        return $this->_dir;
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

	private function addHeader(): array
	{
		$header = $this->getDir() . "Header-" . __VERSION__ . ".html.twig";
		$headerFile = self::PATH . $header;

		if (!file_exists($headerFile) || !is_readable($headerFile)) {
			return [];
		}

		return [
			// its variables
			'HEADER_'	=> $header
		];
	}

	private function addMain(): array
	{
		$main = $this->getDir() . "Main-" . __VERSION__ . ".html.twig";
		$mainFile = self::PATH . $main;

		if (!file_exists($mainFile) || !is_readable($mainFile)) {
			return [];
		}

		return [
			// its variables
			'MAIN_' => $main
		];
	}

	private function addFooter(): array
	{
		$footer = $this->getDir() . "Footer-" . __VERSION__ . ".html.twig";
		$footerFile = self::PATH . $footer;

		if (!file_exists($footerFile) || !is_readable($footerFile)) {
			return [];
		}

		return [
			// its variables
			'FOOTER_' => $footer
		];
	}

	public function renderLayout(): string  
    {
		$loader = new FilesystemLoader(self::PATH);
		$twig	= new Environment($loader);

		$glbs = array(
			'TITLE_'		=> $this->getTitle(),
			'DESCRIPTION_'	=> $this->getDescription(),
			'KEYWORDS_'		=> $this->getKeywords(),
			'AUTHOR_'		=> __AUTHOR__,
			'URL_'			=> __URL__,
			'ROBOTS_'		=> $this->getRobots(),
			'CSS_'			=> __CSS__,
			'DIR_'			=> $this->getDir(),
			'VERSION_'		=> __VERSION__,
			'JS_'			=> __JS__,
			'IMG_'			=> __IMG__
		);

		$vars = array_merge(
			$glbs,
			$this->addHeader(),
			$this->addMain(),
			$this->addFooter()
		);

		return $twig->render('HTMLLayout.html.twig', $vars);
	}
}
