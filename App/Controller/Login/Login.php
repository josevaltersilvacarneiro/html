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

namespace Josevaltersilvacarneiro\Html\App\Controller\Login;

use Josevaltersilvacarneiro\Html\App\Controller\HTMLController;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\SessionEntityInterface;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Response;

/**
 * This controllers renders the login page.
 * 
 * @category  Login
 * @package   Josevaltersilvacarneiro\Html\App\Controllers\Login
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.6.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Cotrollers
 */
final class Login extends HTMLController
{
    /**
     * Initializes the controller.
     * 
     * @param SessionEntityInterface $session session
     */
    public function __construct(private readonly SessionEntityInterface $session)
    {
        $this->setDir('Login');
        $this->setPage('Login');
        $this->setTitle('Login');
        $this->setDescription(
            'The login page provides a simple and secure interface for users
			to authenticate and access the system. It offers a username and
			password input field for users to enter their credentials and a
			login button to initiate the authentication process.'
        );
        $this->setKeywords('MVC SOLID josevaltersilvacarneiro login');
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

        return new Response(
            200, ['Content-Type' => 'text/html;charset=UTF-8'],
            parent::renderLayout()
        );
    }
}
