<?php

declare(strict_types=1);

namespace Josevaltersilvacarneiro\Html\Tests\App\Model\Entity;

use Josevaltersilvacarneiro\Html\App\Model\Entity\User;

use Josevaltersilvacarneiro\Html\App\Model\Attributes\IncrementalPrimaryKeyAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\NameAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\EmailAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\HashAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\SaltAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\ActiveAttribute;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testInitialization(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $idOne     = IncrementalPrimaryKeyAttribute::newInstance(1);
        $nameOne   = new NameAttribute('José Valter');
        $emailOne  = new EmailAttribute('git@josevaltersilvacarneiro.net');
        $hashOne   = new HashAttribute('$2y$10$I8dud/n/.ew89tN/wZ8xw.zEi6U1zrJfS1c8ffqpKIaklmKIw.Wse');
        $saltOne   = new SaltAttribute('c1pyo375pqt');
        $activeOne = ActiveAttribute::newInstance(true);

        $user1 = new User(
            $idOne,
            $nameOne,
            $emailOne,
            $hashOne,
            $saltOne,
            $activeOne
        );

        $this->assertEquals($nameOne->getRepresentation(), $user1->getFullname()->getRepresentation());
        $this->assertEquals('José', $user1->getFullname()->getFirstName());

        $idTwo     = IncrementalPrimaryKeyAttribute::newInstance(-1);
        $nameTwo   = new NameAttribute('José Carneiro');
        $emailTwo  = new EmailAttribute('git@josevaltersilvacarneiro.net');
        $hashTwo   = new HashAttribute('$2y$10$I8dud/n/.ew89tN/wZ8xw.zEi6U1zrJfS1c8ffqpKIaklmKIw.Wse');
        $saltTwo   = new SaltAttribute('c1p');
        $activeTwo = ActiveAttribute::newInstance(false);

        $user2 = new User(
            $idTwo,
            $nameTwo,
            $emailTwo,
            $hashTwo,
            $saltTwo,
            $activeTwo
        );

        $this->assertEquals('José Carneiro', $user2->getFullname()->getRepresentation());
        $this->assertEquals('José', $user2->getFullname()->getFirstName());
    }
}
