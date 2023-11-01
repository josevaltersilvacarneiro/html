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

namespace Josevaltersilvacarneiro\Html\App\Controller\Recover;

use Josevaltersilvacarneiro\Html\App\Controller\HTMLController;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\SessionEntityInterface;

use Josevaltersilvacarneiro\Html\Src\Traits\EmailAuthenticatorTrait;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Response;

/**
 * This class is responsible for rendering the page that
 * resets the user's password.
 * 
 * @category  ResetPassword
 * @package   Josevaltersilvacarneiro\Html\App\Controllers\Recover
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Cotrollers
 */
final class ResetPassword extends HTMLController
{
    use EmailAuthenticatorTrait;

    /**
     * Initializes the controller.
     * 
     * @param SessionEntityInterface $session session
     */
    public function __construct(private readonly SessionEntityInterface $session)
    {
        $this->setPage('ResetPassword');
        $this->setTitle('Type a new password');
        $this->setDescription(
            'This page allows users to reset their account password.'
        );
        $this->setKeywords('MVC SOLID josevaltersilvacarneiro reset');
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

        $code = filter_input(INPUT_GET, 'code');
        if ($code === false || $code === null) {
            return new Response(302, ['Location' => '/recover']);
        }

        $email = filter_input(INPUT_GET, 'email', FILTER_VALIDATE_EMAIL);
        if ($email === false || $email === null) {
            return new Response(302, ['Location' => '/recover']);
        }

        $hash = filter_input(INPUT_GET, 'hash');
        if ($hash === false || $hash === null) {
            return new Response(302, ['Location' => '/recover']);
        }

        if (!$this->isEmailAuthenticated($code, $email, $hash)) {
            return new Response(302, ['Location' => '/recover']);
        }

        return new Response(200, body: $this->renderLayout());
    }
}
