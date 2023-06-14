<?php

/**
 * This package is responsible for sending
 * and receiving emails.
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
 * @package     Josevaltersilvacarneiro\Html\Src\Classes\Mail
 */

namespace Josevaltersilvacarneiro\Html\Src\Classes\Mail;

require_once 'AWSSes.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * This class instantiates the PHPMailer,
 * sets the parameters and sends the mes-
 * sage.
 *
 * @var PHPMailer $mail the object
 * 
 * @method void setFrom(string $emailAddress, string $name = 'HTML')    sets up the mailer
 * @method void setReply(string $emailAddress, string $name = 'HTML')   sets up the responder
 * @method void setMessage(string $subject, string $message)            sets up the subject and message
 *
 * @author      José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version     0.1
 * @see         https://github.com/PHPMailer/PHPMailer/tree/master
 * @copyright   Copyright (C) 2023, José V S Carneiro
 * @license     GPLv3
 */

class Mail
{
    private PHPMailer $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer();
        $this->mail->isSMTP();
        //$this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $this->mail->SMTPDebug = SMTP::DEBUG_OFF;

        $this->mail->Host = _HOSTMAIL;
        $this->mail->Port = _HOST_PORT;
        
        $this->mail->SMTPAuth = true;

        $this->mail->Username = _USERNAME;
        $this->mail->Password = _PASSWORD;
    }

    public function setFrom(string $emailAddress, string $name = 'HTML'): void
    {
        $this->mail->setFrom($emailAddress, $name);
    }

    public function setReply(string $emailAddress, string $name = 'HTML'): void
    {
        $this->mail->addReplyTo($emailAddress, $name);
    }

    public function setReceiver(string $emailAddress, string $name = 'HTML'): void
    {
        $this->mail->addAddress($emailAddress, $name);
    }

    public function setMessage(string $subject, string $message): void
    {
        $this->mail->Subject = $subject;
        $this->mail->msgHTML($message);
        $this->mail->isHTML(true);
        $this->mail->AltBody = $message;
    }

    public function send(): bool
    {
        return $this->mail->send();
    }

    public function getError(): string
    {
        return $this->mail->ErrorInfo;
    }
}
