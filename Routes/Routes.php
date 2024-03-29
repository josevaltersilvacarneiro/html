<?php

declare(strict_types=1);

/**
 * Where the routes are defined.
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
 * @category Routes
 * @package  Josevaltersilvacarneiro\Html\Routes
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Routes
 */

return [
    'GET|/' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Home\Home::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\Session::class,
        ]
    ],
    'GET|/home' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Home\Home::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\Session::class,
        ]
    ],
    'GET|/sign' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Sign\Sign::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\Session::class,
        ]
    ],
    'GET|/login' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Login\Login::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\Session::class,
        ]
    ],
    'POST|/login/signin' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Login\Signin::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\Session::class,
        ]
    ],
    'GET|/logout' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Login\Signout::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\Session::class,
        ]
    ],
    'GET|/recover' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Recover\Recover::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\Session::class,
        ]
    ],
    'POST|/recover/process' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Recover\ProcessRecovery::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\Session::class,
            \Josevaltersilvacarneiro\Html\Src\Classes\Mail\Mail::class,
        ]
    ],
    'GET|/recover/reset' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Recover\ResetPassword::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\Session::class,
        ]
    ],
    'POST|/recover/newpassword' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Recover\NewPassword::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\Session::class
        ]
    ],
    'POST|/login/signout' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Login\Signout::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\Session::class,
        ]
    ],
    'GET|/register' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Register\Register::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\Session::class,
        ]
    ],
    'POST|/register/signup' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Register\Signup::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\Session::class,
            \Josevaltersilvacarneiro\Html\Src\Classes\Mail\Mail::class,
        ]
    ],
    'GET|/confirm/email' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Confirm\Email::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\Session::class,
        ]
    ],

    # SOLID

    'GET|/solid' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Home\Solid::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\Session::class,
        ]
    ],
];
