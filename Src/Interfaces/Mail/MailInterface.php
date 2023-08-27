<?php

declare(strict_types=1);

/**
 * Based on the project requirements, the interfaces will be written.
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
 * @category Mail
 * @package  Josevaltersilvacarneiro\Html\Src\Interfaces\Mail
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Mail
 */

namespace Josevaltersilvacarneiro\Html\Src\Interfaces\Mail;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Dependency\DependencyInterface;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Exceptions\MailExceptionInterface;

/**
 * This interface represents a "mailman".
 * 
 * @category  MailInterface
 * @package   Josevaltersilvacarneiro\Html\Src\Interfaces\Mail
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Interfaces/Mail
 */
interface MailInterface extends DependencyInterface
{
    /**
     * Sets sender.
     * 
     * @param string $email    Email address of the person sending this message
     * @param string $fullname Complete name of the person sending this message
     * 
     * @return static itself
     */
    public function setSender(string $email, string $fullname): static;

    /**
     * Sets reply.
     * 
     * @param string $email    Email address of the person receiving the reply
     * @param string $fullname Complete name of the person receiving the reply
     * 
     * @return static itself
     */
    public function setReplyTo(string $email, string $fullname): static;

    /**
     * Sets subjact.
     * 
     * @param string $subject Subject of the message @example "Recovery Access"
     * 
     * @return static itself
     */
    public function setSubject(string $subject): static;

    /**
     * Sets message.
     * 
     * @param string $body Message
     * 
     * @return static itself
     */
    public function setBody(string $body): static;

    /**
     * This method is responsible for adding the recipient of the message.
     * It will be called many times, once for each recipient. Maybe you need
     * a data structure to store them.
     * 
     * @param string $email    Email address of the person will receive the message
     * @param string $fullname Complete name of the person will receive the message
     * 
     * @return static itself
     */
    public function addRecipient(string $email, string $fullname): static;

    /**
     * This method is responsible for sending the message.
     * 
     * @return bool true if the message was sent successfully, false otherwise
     * @throws MailExceptionInterface If the message cannot be sent
     */
    public function send(): bool;
}
