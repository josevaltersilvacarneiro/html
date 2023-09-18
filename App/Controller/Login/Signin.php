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

use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\SessionEntityInterface;
use Josevaltersilvacarneiro\Html\App\Model\Entity\User;

use Josevaltersilvacarneiro\Html\Src\Classes\Exceptions\EntityException;

use Josevaltersilvacarneiro\Html\App\Model\Attributes\EmailAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\HashAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\SaltAttribute;

use Josevaltersilvacarneiro\Html\Src\Classes\Exceptions\AttributeException;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This class processes the user login form.
 * 
 * @category  Signin
 * @package   Josevaltersilvacarneiro\Html\App\Controllers\Login
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Cotrollers
 */
final class Signin implements RequestHandlerInterface
{
    /**
     * Initializes the controller.
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

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if ($email === false || $email === null) {
            return new Response(302, ['Location' => '/login']);
        }

        $hash = filter_input(INPUT_POST, 'hash', FILTER_SANITIZE_STRING);
        if ($hash === false || $hash === null) {
            return new Response(302, ['Location' => '/login']);
        }

        $newHash = filter_input(INPUT_POST, 'newHash', FILTER_SANITIZE_STRING);
        if ($newHash === false || $newHash === null) {
            return new Response(302, ['Location' => '/login']);
        }

        $newSalt = filter_input(INPUT_POST, 'newSalt', FILTER_SANITIZE_STRING);
        if ($newSalt === false || $newSalt === null) {
            return new Response(302, ['Location' => '/login']);
        }

        try {
            $email   = new EmailAttribute($email);
            $hash    = new HashAttribute($hash);
            $newHash = new HashAttribute($newHash);
            $newSalt = new SaltAttribute($newSalt);
        } catch (AttributeException $e) {
            $e->storeLog();
            return new Response(302, ['Location' => '/login']);
        }

        $user = User::newInstance($email);
        if (is_null($user) || !$hash::areHashesEqual($hash, $user->getHash())) {
            return new Response(302, ['Location' => '/login']);
        }

        // note that the user's password is changed every
        // time the user logs in

        $user->setPassword($newHash, $newSalt);

        try {
            $this->session->setUser($user);
        } catch (EntityException $e) {
            $e->storeLog();
            return new Response(302, ['Location' => '/login']);
        }

        if (!$this->session->flush()) {
            return new Response(302, ['Location' => '/login']);
        }

        return new Response(302, ['Location' => '/']);
    }
}
