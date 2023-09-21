<?php

declare(strict_types=1);

namespace Josevaltersilvacarneiro\Html\Tests\Src\Classes\Sql;

use Josevaltersilvacarneiro\Html\Src\Classes\Queries\DatabaseStandard;

use PHPUnit\Framework\TestCase;

class DatabaseStandardTest extends TestCase
{
    /**
     * @dataProvider returnQueries
     */
    public function testQueries(string $table, string $primaryKey,
        string $query): void
    {
        $record = DatabaseStandard::generateDeleteStandard($table, $primaryKey);

        $this->assertNotEquals($query, $record);
    }

    public static function returnQueries(): array
    {
        return [
            ['tbUser', 'userID', 'DELETE FROM `tbUser` WHERE userID = :userID;'],
        ];
    }
}
