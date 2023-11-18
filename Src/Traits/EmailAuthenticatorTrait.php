<?php

declare(strict_types=1);

/**
 * This package is responsible for offering useful functions.
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
 * @category Traits
 * @package  Josevaltersilvacarneiro\Html\Src\Traits
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Traits
 */

namespace Josevaltersilvacarneiro\Html\Src\Traits;

use Josevaltersilvacarneiro\Html\Src\Interfaces\Mail\MailInterface;
use Josevaltersilvacarneiro\Html\Src\Interfaces\Exceptions\MailExceptionInterface;

use Josevaltersilvacarneiro\Html\Src\Traits\EmailValidatorTrait;
use Josevaltersilvacarneiro\Html\Src\Traits\CryptTrait;

/**
 * This trait offers methods authentication by email.
 * 
 * @category  EmailAuthenticatorTrait
 * @package   Josevaltersilvacarneiro\Html\Src\Traits
 * @author    José Carneiro <git@josevaltersilvacarneiro.net>
 * @copyright 2023 José Carneiro
 * @license   GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @version   Release: 0.0.2
 * @link      https://github.com/josevaltersilvacarneiro/html/tree/main/Src/Traits
 */
trait EmailAuthenticatorTrait
{
    use EmailValidatorTrait, CryptTrait;

    private const _SENDER_NAME = 'José Carneiro';
    private const _SENDER      = 'me@message.josevaltersilvacarneiro.net';
    private const _REPLY_NAME  = 'José Carneiro';
    private const _REPLY       = 'me@message.josevaltersilvacarneiro.net';

    /**
     * Sends an email with a link to confirm the email.
     * 
     * @param MailInterface $mail     The mail object
     * @param string        $url      The url to be sent in the email
     * @param string        $email    The email to be sent
     * @param string        $title    The title of the email
     * @param string        $message  The message to be sent
     * @param string        $name     The name of the recipient
     * @param string        $password The password to be sent
     * 
     * @return bool True if the email was sent or false if an error occurs
     */
    private function sendConfirmationEmail(
        MailInterface $mail,
        string $url,
        string $email,
        string $title,
        string $message,
        string $name = 'User',
        string $password = ''
    ): bool {
        $code = self::_generateEmailCode();
        $hash = self::_generateEmailCodeHash($email, $code . $password);

        $encryptedCode = self::_encrypt($code, self::_PASSWORD);

        if ($encryptedCode === false) {
            return false;
        }

        $encryptedCode = urlencode($encryptedCode);
        $hash          = urlencode($hash);
        $message = <<<MESSAGE
            <p>$message</p>
            <a href="{$url}?email=$email&code=$encryptedCode&hash=$hash">Click</a>
        MESSAGE;

        try {
            $mail->setSender(self::_SENDER, self::_SENDER_NAME)
                ->setReplyTo(self::_REPLY, self::_REPLY_NAME)
                ->setSubject($title)
                ->setBody($message)
                ->addRecipient($email, $name)
                ->send();
        } catch (MailExceptionInterface $e) {
            $e->storeLog();
            return false;
        }

        return true;
    }

    /**
     * Confirms the email.
     * 
     * @param string $code     The code to be confirmed
     * @param string $email    The email to be confirmed
     * @param string $hash     The hash to be confirmed
     * @param string $password The password to be confirmed
     * 
     * @return bool True if the email was confirmed or false if an error occurs
     */
    private function isEmailAuthenticated(
        string $code,
        string $email,
        string $hash,
        string $password = ''
    ): bool {
        $decryptedCode = self::_decrypt($code, self::_PASSWORD);
        if ($decryptedCode === false) {
            return false;
        }

        return self::_isCodeHashValid($email, $decryptedCode . $password, $hash);
    }
}
