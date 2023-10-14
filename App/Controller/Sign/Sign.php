<?php

declare(strict_types=1);

/**
 * This is a comprehensive PHP package designed to streamline the development
 * of controllers in the application following the MVC (Model-View-Controller)
 * architectural pattern. It provides a set of powerful tools and utilities to
 * handle user input, orchestrate application logic, and facilitate seamless
 * communication between the Model and View components.
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
 * @category Controllers
 * @package  Josevaltersilvacarneiro\Html\App\Controllers
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/App/Controllers
 */

namespace Josevaltersilvacarneiro\Html\App\Controller\Sign;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\SessionEntityInterface;
use Josevaltersilvacarneiro\Html\App\Controller\HTMLController;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * This class returns the sign page.
 * 
 * @category  Sign
 * @package   Josevaltersilvacarneiro\Html\App\Controllers\Sign
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Cotrollers
 */
final class Sign extends HTMLController
{
    /**
     * Initializes the controller.
     * 
     * @param SessionEntityInterface $session session
     */
    public function __construct(private readonly SessionEntityInterface $session)
    {
        $this->setPage('Sign');
        $this->setTitle('Sign');
        $this->setDescription(
            'The sign page provides two buttons for users click to sign in or
			sign up.'
        );
        $this->setKeywords('MVC SOLID josevaltersilvacarneiro sign');
    }

    /**
     * Handles the request and produces a response.
     * 
     * @param ServerRequestInterface $request request
     * 
     * @return ResponseInterface response
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->session->isUserLogged()) {
            return new Response(302, ['Location' => '/']);
        }

        return new Response(200, body: $this->renderLayout());
    }
}
