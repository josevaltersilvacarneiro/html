<?php

/**
 * This package is responsible for sending and receiving emails.
 * PHP VERSION 8.2.0
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
 * @package  Josevaltersilvacarneiro\Html\Src\Classes\Mail
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Classes/Mail
 */

namespace Josevaltersilvacarneiro\Html\Src\Classes\Mail;

require_once 'AWSSes.php';

use Josevaltersilvacarneiro\Html\Src\Classes\Exceptions\MailException;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Mail\MailInterface;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * This class instantiates the PHPMailer, sets the parameters
 * and sends the message.
 * 
 * @var PHPMailer $_mailman the object
 * 
 * @method static setSender(string $email, string $fullname)    sets up the sender
 * @method static setReplyTo(string $email, string $fullname)   sets up the responder
 * @method static setSubject(string $subject)                   sets up the subject
 * @method static setBody(string $body)                         sets up the body
 * @method static addRecipient(string $email, string $fullname) adds a recipient
 * @method static send()                                        sends the message
 * 
 * @category  Mail
 * @package   Josevaltersilvacarneiro\Html\Src\Classes\Mail
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.2.1
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Classes/Mail
 * @see       https://github.com/PHPMailer/PHPMailer/tree/master
 */
class Mail implements MailInterface
{
    private readonly PHPMailer $_mailman;

    /**
     * Initializes the mailman.
     */
    public function __construct()
    {
        $this->_mailman = new PHPMailer();
        $this->_mailman->isSMTP();
        //$this->mailman->SMTPDebug = SMTP::DEBUG_SERVER;
        $this->_mailman->SMTPDebug = SMTP::DEBUG_OFF;

        $this->_mailman->Host = _HOSTMAIL;
        $this->_mailman->Port = _HOST_PORT;
        
        $this->_mailman->SMTPAuth = true;

        $this->_mailman->Username = _USERNAME;
        $this->_mailman->Password = _PASSWORD;
    }

    /**
     * Forks the mailman.
     * 
     * @param array $dependencies the dependencies
     * 
     * @return static|false itself on success, false otherwise
     */
    public static function fork(array $dependencies = []): static|false
    {
        return new static();
    }

    /**
     * Sets up the sender.
     * 
     * @param string $email    the email
     * @param string $fullname the fullname
     * 
     * @return static itself
     */
    public function setSender(string $email, string $fullname): static
    {
        $this->_mailman->setFrom($email, $fullname);

        return $this;
    }

    /**
     * Sets up the responder.
     * 
     * @param string $email    the email
     * @param string $fullname the fullname
     * 
     * @return static itself
     */
    public function setReplyTo(string $email, string $fullname): static
    {
        $this->_mailman->addReplyTo($email, $fullname);

        return $this;
    }

    /**
     * Sets up the subject.
     * 
     * @param string $subject the subject
     * 
     * @return static itself
     */
    public function setSubject(string $subject): static
    {
        $this->_mailman->Subject = $subject;

        return $this;
    }

    /**
     * Sets up the body.
     * 
     * @param string $body the body
     * 
     * @return static itself
     */
    public function setBody(string $body): static
    {
        $this->_mailman->msgHTML($body);
        $this->_mailman->isHTML(true);
        $this->_mailman->AltBody = $body;

        return $this;
    }

    /**
     * Adds a recipient.
     * 
     * @param string $email    the email
     * @param string $fullname the fullname
     * 
     * @return static itself
     */
    public function addRecipient(string $email, string $fullname): static
    {
        $this->_mailman->addAddress($email, $fullname);

        return $this;
    }

    /**
     * Sends the message.
     * 
     * @return bool true on success, false otherwise
     * @throws MailException With the error code if the message is not sent
     */
    public function send(): bool
    {
        if (! $this->_mailman->send()) {
            throw new MailException($this->_mailman->ErrorInfo);
        }

        return false;
    }
}
