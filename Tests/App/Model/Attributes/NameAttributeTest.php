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

use Josevaltersilvacarneiro\Html\App\Model\Attributes\NameAttribute;

use Josevaltersilvacarneiro\Html\Src\Classes\Exceptions\AttributeException;

use PHPUnit\Framework\TestCase;

class NameAttributeTest extends TestCase
{
    /**
     * @dataProvider validNameProvider
     */
    public function testInitialization(string $name): void
    {
        $fullname = new NameAttribute($name);
        $this->assertInstanceOf(NameAttribute::class, $fullname);
    }

    public static function validNameProvider(): array
    {
        return [
            'Starting with small letters' => ['josé da silva'],
            'Starting capitalized' => ['João Maria'],
        ];
    }

    public function testInitializationError(): void
    {
        // The fullname must start with three letters and end with three letters
        $this->expectException(AttributeException::class);
        new NameAttribute('José V');
    }

    public function testIndividual(): void
    {
        $fullname = new NameAttribute('josé da silva carneiro');
        $this->assertEquals('José', $fullname->getFirstName());
        $this->assertEquals('Carneiro', $fullname->getLastName());
    }
}
