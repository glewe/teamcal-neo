<?php
namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\ConfigModel;
use App\Models\DbModel;

class ConfigModelTest extends TestCase
{
  public function testInstantiate() {
    // Mock PDO
    $pdoMock  = $this->createMock(\PDO::class);
    $stmtMock = $this->createMock(\PDOStatement::class);

    // Expect query in load()
    $pdoMock->method('prepare')->willReturn($stmtMock);
    $stmtMock->method('execute')->willReturn(true);
    $stmtMock->method('fetchAll')->willReturn([]);

    // Mock DbModel
    $dbMock     = $this->createMock(DbModel::class);
    $dbMock->db = $pdoMock;

    $conf = ['db_table_config' => 'test_config'];

    $config = new ConfigModel($conf, $dbMock);

    $this->assertInstanceOf(ConfigModel::class, $config);
  }
}
