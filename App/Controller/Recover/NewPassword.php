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

use Josevaltersilvacarneiro\Html\App\Model\Entity\User;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\SessionEntityInterface;

use Josevaltersilvacarneiro\Html\App\Model\Attributes\EmailAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\HashAttribute;

use Josevaltersilvacarneiro\Html\Src\Traits\EmailAuthenticatorTrait;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This controller changes the user password.
 * 
 * @category  NewPassword
 * @package   Josevaltersilvacarneiro\Html\App\Controllers\Recover
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.3
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Cotrollers
 */
final class NewPassword implements RequestHandlerInterface
{
    use EmailAuthenticatorTrait;

    /**
     * Initializes the controller.
     * 
     * @param SessionEntityInterface $_session session
     */
    public function __construct(
        private readonly SessionEntityInterface $_session
    ) {
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
        if ($this->_session->isUserLogged()) {
            return new Response(302, ['Location' => '/']);
        }

        // get the parameters

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

        $newPassword = filter_input(INPUT_POST, 'password');
        if ($newPassword === false || $newPassword === null) {
            return new Response(302, ['Location' => '/recover']);
        }

        // get the user if it exists

        $user = User::newInstance(EmailAttribute::newInstance($email));
        if ($user === null) {
            return new Response(302, ['Location' => '/recover']);
        }

        // check if the request is authenticated

        if (!$this->isEmailAuthenticated($code, $email, $hash, $user->getHash()->getRepresentation())) {
            return new Response(302, ['Location' => '/recover']);
        }

        // update the user password

        $user->setPassword(HashAttribute::newInstance($newPassword));

        if (!$user->flush()) {
            // it wasn't possible to update the user password
            return new Response(302, ['Location' => '/recover']);
        }

        // all right! redirect to the login page
    
        return new Response(302, ['Location' => '/login']);
    }
}
