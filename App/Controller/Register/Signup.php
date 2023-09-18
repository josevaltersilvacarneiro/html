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
use Josevaltersilvacarneiro\Html\App\Model\Entity\User;

use Josevaltersilvacarneiro\Html\App\Model\Attributes\NameAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\EmailAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\HashAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\SaltAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\ActiveAttribute;

use Josevaltersilvacarneiro\Html\Src\Classes\Exceptions\AttributeException;

use Josevaltersilvacarneiro\Html\Src\Traits\EmailValidatorTrait;

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
 * @version   Release: 0.0.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Cotrollers
 */
class Signup implements RequestHandlerInterface
{
    use EmailValidatorTrait;

    /**
     * Initialize the object.
     * 
     * @param SessionEntityInterface $session session
     */
    public function __construct(private readonly SessionEntityInterface $session)
    {
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

        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        if ($name === false || $name === null) {
            return new Response(302, ['Location' => '/register']);
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if ($email === false || $email === null) {
            return new Response(302, ['Location' => '/register']);
        }

        $hash = filter_input(INPUT_POST, 'hash', FILTER_SANITIZE_STRING);
        if ($hash === false || $hash === null) {
            return new Response(302, ['Location' => '/register']);
        }

        $salt = filter_input(INPUT_POST, 'salt', FILTER_SANITIZE_STRING);
        if ($salt === false || $salt === null) {
            return new Response(302, ['Location' => '/register']);
        }

        $code = filter_input(INPUT_POST, 'code', FILTER_SANITIZE_STRING);
        if ($code === false || $code === null) {
            return new Response(302, ['Location' => '/register']);
        }

        $hashCode = filter_input(INPUT_POST, 'hash_code', FILTER_SANITIZE_STRING);
        if ($hashCode === false || $hashCode === null) {
            return new Response(302, ['Location' => '/register']);
        }

        try {
            $name  = new NameAttribute($name);
            $email = new EmailAttribute($email);
            $hash  = new HashAttribute($hash);
            $salt  = new SaltAttribute($salt);
            $actv  = new ActiveAttribute(true);
        } catch (AttributeException $e) {
            $e->storeLog();
            return new Response(302, ['Location' => '/register']);
        }

        if (!$this->_isCodeHashValid(
            $email->getRepresentation(),
            $code,
            $hashCode
        )
        ) {
            return new Response(302, ['Location' => '/register']);
        }

        $user = new User(null, $name, $email, $hash, $salt, $actv);
        if (!$user->flush()) {
            return new Response(302, ['Location' => '/register']);
        }

        $this->session->setUser($user);
        $this->session->flush();

        // If the session cannot be updated, the user will have to log in again

        return new Response(302, ['Location' => '/']);
    }
}
