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

namespace Josevaltersilvacarneiro\Html\App\Controller\Confirm;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Entities\SessionEntityInterface;

use Josevaltersilvacarneiro\Html\App\Model\Entity\User;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\EmailAttribute;

use Josevaltersilvacarneiro\Html\Src\Traits\EmailValidatorTrait;
use Josevaltersilvacarneiro\Html\Src\Traits\CryptTrait;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This class processes the account confirmation link.
 * 
 * @category  Email
 * @package   Josevaltersilvacarneiro\Html\App\Controllers\Confirm
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/App/Cotrollers
 */
final class Email implements RequestHandlerInterface
{
    use EmailValidatorTrait, CryptTrait;

    /**
     * Initializes the controller.
     * 
     * @param SessionEntityInterface $_session session
     */
    public function __construct(private readonly SessionEntityInterface $_session)
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
        if ($this->_session->isUserLogged()) {
            return new Response(302, ['Location' => '/']);
        }

        $email = filter_input(INPUT_GET, 'email', FILTER_VALIDATE_EMAIL);
        if ($email === false || $email === null) {
            return new Response(302, ['Location' => '/register']);
        }

        $code = filter_input(INPUT_GET, 'code');
        if ($code === false || $code === null) {
            return new Response(302, ['Location' => '/register']);
        }

        $decryptedCode = self::_decrypt($code, self::_PASSWORD);
        if ($decryptedCode === false) {
            return new Response(302, ['Location' => '/register']);
        }

        $hash = filter_input(INPUT_GET, 'hash');
        if ($hash === false || $hash === null) {
            return new Response(302, ['Location' => '/register']);
        }

        if (!self::_isCodeHashValid($email, $decryptedCode, $hash)) {
            // sleep to avoid user enumeration attack
            return new Response(302, ['Location' => '/register']);
        }

        $user = User::newInstance(EmailAttribute::newInstance($email));
        if (is_null($user)) {
            return new Response(302, ['Location' => '/register']);
        }

        if (!$user->isActive()) {
            $user->makeMeActive();
        }

        if (!$this->_session->setUser($user)->flush()) {
            return new Response(302, ['Location' => '/login']);
        }

        return new Response(302, ['Location' => '/']);
    }
}
