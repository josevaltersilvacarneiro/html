<?php

declare(strict_types=1);

/**
 * This comprehensive PHP package is designed to simplify the
 * process of setting up and managing global variables. It
 * provides a convenient and organized approach to define,
 * access, and share variables across different components
 * of the application.
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
 * @package	Settings
 */

return [
    'GET|/' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Home\Home::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\AppEntity\UserSession::class,
        ]
    ],
    'GET|/home' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Home\Home::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\AppEntity\UserSession::class,
        ]
    ],
    'GET|/login' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Login\Login::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\AppEntity\UserSession::class,
        ]
    ],
    'POST|/login/signin' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Login\Signin::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\AppEntity\UserSession::class,
        ]
    ],
    'POST|/login/signout' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Login\Signout::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\AppEntity\UserSession::class,
        ]
    ],
    'GET|/register' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Register\Register::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\AppEntity\UserSession::class,
        ]
    ],
    'POST|/register/signup' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Register\Signup::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\App\Model\Entity\AppEntity\UserSession::class,
        ]
    ],

    # API

    'POST|/api/search/salt' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Api\Search\Salt::class,
        'dependencies' => []
    ],
    'POST|/api/confirm/email' => [
        'controller' => \Josevaltersilvacarneiro\Html\App\Controller\Api\Confirm\Email::class,
        'dependencies' => [
            \Josevaltersilvacarneiro\Html\Src\Classes\Mail\Mail::class
        ]
    ],
];
