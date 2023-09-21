<?php

declare(strict_types=1);

namespace Josevaltersilvacarneiro\Html\Tests\App\Model\Entity;

use Josevaltersilvacarneiro\Html\App\Model\Entity\Session;

use Josevaltersilvacarneiro\Html\App\Model\Attributes\{
    GeneratedPrimaryKeyAttribute};

use Josevaltersilvacarneiro\Html\App\Model\Entity\Request;

use Josevaltersilvacarneiro\Html\App\Model\Attributes\IncrementalPrimaryKeyAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\IpAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\PortAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\DateAttribute;

use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    public function testInitialization(): void
    {
        $id      = IncrementalPrimaryKeyAttribute::newInstance(20);
        $ip      = new IpAttribute('192.168.0.56');
        $port    = new PortAttribute('56364');
        $access  = new DateAttribute();
        $request = new Request($id, $ip,$port, $access);

        $sessionId = new GeneratedPrimaryKeyAttribute(GeneratedPrimaryKeyAttribute::generatePrimaryKey());

        $session = new Session(
            $sessionId,
            null,
            $request
        );

        $this->assertEquals($sessionId->getRepresentation(), $session->getId()->getRepresentation());
        $this->assertEquals(false, $session->isUserLogged());
        $this->assertEquals(false, $session->isExpired());
    }
}
