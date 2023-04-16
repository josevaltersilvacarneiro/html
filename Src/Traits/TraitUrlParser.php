<?php

namespace Src\Traits;

trait TraitUrlParser
{
	/**
	 * This method returns a array containing the url arguments.
	 * For example:
	 *
	 * http://test.com/foo/bar => array {
	 * 	[0] => string(3) "foo",
	 * 	[1] => string(3) "bar",
	 * }
	 *
	 * @return array
	 */

	protected function getUrl() {
		return explode('/', rtrim($_GET['url']), FILTER_SANITIZE_URL);
	}
}
