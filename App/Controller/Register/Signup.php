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

namespace Josevaltersilvacarneiro\Html\App\Controller\Register;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\SessionEntityInterface;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Mail\MailInterface;
use Josevaltersilvacarneiro\Html\App\Model\Entity\User;

use Josevaltersilvacarneiro\Html\App\Model\Attributes\NameAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\EmailAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\HashAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\ActiveAttribute;

use Josevaltersilvacarneiro\Html\Src\Classes\Exceptions\AttributeException;

use Josevaltersilvacarneiro\Html\Src\Traits\EmailAuthenticatorTrait;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * This class processes the user registration form.
 * 
 * @category  Signup
 * @package   Josevaltersilvacarneiro\Html\App\Controllers\Register
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.1.2
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Cotrollers
 */
class Signup implements RequestHandlerInterface
{
    use EmailAuthenticatorTrait;

    /**
     * Initialize the object.
     * 
     * @param SessionEntityInterface $session session
     */
    public function __construct(
        private readonly SessionEntityInterface $session,
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
        if ($this->session->isUserLogged()) {
            return new Response(302, ['Location' => '/']);
        }

        $name = filter_input(INPUT_POST, 'name');
        if ($name === false || $name === null) {
            return new Response(302, ['Location' => '/register']);
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if ($email === false || $email === null) {
            return new Response(302, ['Location' => '/register']);
        }

        $hash = filter_input(INPUT_POST, 'password');
        if ($hash === false || $hash === null) {
            return new Response(302, ['Location' => '/register']);
        }

        try {
            $name  = new NameAttribute($name);
            $email = new EmailAttribute($email);
            $hash  = new HashAttribute($hash);
            $actv  = new ActiveAttribute(false);
        } catch (AttributeException $e) {
            $e->storeLog();
            return new Response(302, ['Location' => '/register']);
        }

        $user = new User(null, $name, $email, $hash, $actv);
        if (!$user->flush()) {
            return new Response(302, ['Location' => '/register']);
        }

        // confirm email

        if (!$this->sendConfirmationEmail(
            $this->_mail,
            __URL__ . 'confirm/email',
            $email->getRepresentation(),
            'Confirm your email address',
            'Confirm your email address by clicking on the link below:'
        )
        ) {
            return new Response(302, ['Location' => '/recover']);
        }

        return new Response(302, ['Location' => '/login']);
    }
}
