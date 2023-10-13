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

use Josevaltersilvacarneiro\Html\App\Model\Attributes\IpAttribute;
use Josevaltersilvacarneiro\Html\Src\Classes\Exceptions\AttributeException;

use PHPUnit\Framework\TestCase;

class IpAttributeTest extends TestCase
{
    /**
     * @dataProvider validIpProvider
     */
    public function testInitialization(string $ip): void
    {
        $ip = new IpAttribute($ip);
        $this->assertInstanceOf(IpAttribute::class, $ip);
    }

    public static function validIpProvider(): array
    {
        return [
            'IPV6' => ['::1'],
            'IPV4' => ['127.0.0.1']
        ];
    }

    public function testInitializationError(): void
    {
        $this->expectException(AttributeException::class);
        new IpAttribute('192.168.0.256');
    }

    public function testDBInitialization(): void
    {
        $this->assertInstanceOf(IpAttribute::class, IpAttribute::newInstance('192.168.1.1'));
        $this->assertInstanceOf(IpAttribute::class, IpAttribute::newInstance('::2'));
    }
}
