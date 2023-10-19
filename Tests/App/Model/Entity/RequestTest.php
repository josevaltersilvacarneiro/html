<?php

declare(strict_types=1);

namespace Josevaltersilvacarneiro\Html\Tests\App\Model\Entity;

use Josevaltersilvacarneiro\Html\App\Model\Attributes\IpAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\PortAttribute;
use Josevaltersilvacarneiro\Html\App\Model\Attributes\DateAttribute;

use Josevaltersilvacarneiro\Html\App\Model\Entity\Request;

use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testInitialization(): void
    {
        $ip = new IpAttribute('192.168.1.1');
        $port = new PortAttribute('56364');
        $access = new DateAttribute();
        $request = new Request(null, $ip,$port, $access);

        $this->assertInstanceOf(Request::class, $request);
        $this->assertInstanceOf(IpAttribute::class, $request->getIp());
        $this->assertInstanceOf(PortAttribute::class, $request->getPort());
        $this->assertInstanceOf(DateAttribute::class, $request->getDate());

        $this->assertEquals(true, $request->flush());
    }
}
