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
use Josevaltersilvacarneiro\Html\Src\Interfaces\Mail\MailInterface;

use Josevaltersilvacarneiro\Html\App\Model\Attributes\EmailAttribute;

use Josevaltersilvacarneiro\Html\Src\Traits\EmailAuthenticatorTrait;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This class processes the email recovery form.
 * 
 * @category  ProcessRecovery
 * @package   Josevaltersilvacarneiro\Html\App\Controllers\Recover
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.3
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Cotrollers
 */
final class ProcessRecovery implements RequestHandlerInterface
{
    use EmailAuthenticatorTrait;

    /**
     * Initializes the controller.
     * 
     * @param SessionEntityInterface $_session session
     * @param MailInterface          $_mail    mail
     */
    public function __construct(
        private readonly SessionEntityInterface $_session,
        private readonly MailInterface $_mail
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

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if ($email === false || $email === null) {
            return new Response(302, ['Location' => '/recover']);
        }

        $email = EmailAttribute::newInstance($email);
        if (is_null($email) || is_null($user = User::newInstance($email))) {
            return new Response(302, ['Location' => '/recover']);
        }

        if (!$this->sendConfirmationEmail(
            $this->_mail,
            __URL__ . 'recover/reset',
            $email->getRepresentation(),
            'Recover your account',
            'Click the link below to recover your account',
            $user->getFullname()->getFirstName(),
            $user->getHash()->getRepresentation()
        )
        ) {
            return new Response(302, ['Location' => '/recover']);
        }

        return new Response(302, ['Location' => '/login']);
    }
}
