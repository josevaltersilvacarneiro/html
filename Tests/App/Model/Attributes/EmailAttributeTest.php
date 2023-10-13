<?php

declare(strict_types=1);

/**
 * This package contains the unit testing of entity attributes.
 * PHP VERSION >= 8.2.0
 * 
 * Copyright (C) 2023, José Carneiro
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
 * @category Attributes
 * @package  Josevaltersilvacarneiro\Html\Tests\App\Model\Attributes
 * @author   José Carneiro <git@josevaltersilvacarneiro.net>
 * @license  GPLv3 https://www.gnu.org/licenses/quick-guide-gplv3.html
 * @link     https://github.com/josevaltersilvacarneiro/html/tree/main/Tests/App/Model/Attributes
 */

namespace Josevaltersilvacarneiro\Html\Tests\App\Model\Attributes;

use Josevaltersilvacarneiro\Html\App\Model\Attributes\EmailAttribute;
use Josevaltersilvacarneiro\Html\Src\Classes\Exceptions\AttributeException;

use PHPUnit\Framework\TestCase;

class EmailAttributeTest extends TestCase
{
    /**
     * @dataProvider validEmailProvider
     */
    public function testInitialization(string $email): void
    {
        $emailObject = new EmailAttribute($email);
        $this->assertInstanceOf(EmailAttribute::class, $emailObject);

        $this->assertEquals($email, $emailObject->getRepresentation());
    }

    public static function validEmailProvider(): array
    {
        return [
            ['git@josevaltersilvacarneiro.net'],
            ['example@foo.josevaltersilvacarneiro.net'],
            ['bar@foo.net'],
        ];
    }

    public function testInitializationError(): void
    {
        $this->expectException(AttributeException::class);
        new EmailAttribute('josevaltersilvacarneiro.net');
    }

    public function testComplete(): void
    {
        $email = new EmailAttribute('git@josevaltersilvacarneiro.net');
        $this->assertEquals('git', $email->getUsername());
        $this->assertEquals('josevaltersilvacarneiro.net', $email->getDomain());
        $this->assertEquals(['josevaltersilvacarneiro', 'net'], $email->getSubdomains());
    }
}
