<?php

declare(strict_types=1);

namespace Josevaltersilvacarneiro\Html\Tests\Src\Classes\Sql;

use Josevaltersilvacarneiro\Html\Src\Classes\Sql\Connect;
use Josevaltersilvacarneiro\Html\Src\Classes\Sql\Sql;

use PHPUnit\Framework\TestCase;

class SqlTest extends TestCase
{
    /**
     * @dataProvider returnQueriesToTestTransactions
     */
    public function testTransactions(array ...$queries): void
    {
        $repository = new SqlExampleExtension();

        $trans = $repository->queryAll(...$queries);

        $this->assertNotEquals(false, $trans);
    }

    public static function returnQueriesToTestTransactions(): array
    {
        $query1 = <<<QUERY
            INSERT INTO `requests`
            (ip, port, access_date)
            VALUES (:ip, :port, :access_date);
        QUERY;
        $data1 = [
            'ip'          => '192.68.7.89',
            'port'        => '5263',
            'access_date' => '2021-10-10 00:00:00',
        ];

        $param1 = [$query1, $data1];

        $query2 = <<<QUERY
            UPDATE `requests`
            SET ip = :ip
            WHERE request_id = LAST_INSERT_ID();
        QUERY;
        $data2 = [
            'ip' => '192.168.1.4',
        ];

        $param2 = [$query2, $data2];

        // adding a request above

        $query3 = <<<QUERY
            INSERT INTO `sessions`
            (session_id, request)
            VALUES (:session_id, LAST_INSERT_ID());
        QUERY;
        $data3 = [
            'session_id' => '9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08',
        ];

        $param3 = [$query3, $data3];

        $query4 = <<<QUERY
            DELETE FROM `sessions`
            WHERE session_id = :session_id;
        QUERY;
        $data4 = [
            'session_id' => '9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08',
        ];

        $param4 = [$query4, $data4];

        $query5 = <<<QUERY
            DELETE FROM `requests`
            WHERE ip = :ip;
        QUERY;
        $data5 = [
            'ip' => '192.168.1.4',
        ];

        $param5 = [$query5, $data5];

        return [
            'a simple transaction'      => [$param1, $param2, $param3],
            'deleting the items added'  => [$param4, $param5]
        ];
    }
}

class SqlExampleExtension extends Sql
{
    public function __construct()
    {
        parent::__construct(Connect::newMysqlConnection());
    }
}
