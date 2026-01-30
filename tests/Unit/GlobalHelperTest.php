<?php
namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;

// Include the helper file under test
require_once __DIR__ . '/../../src/Helpers/global.helper.php';

class GlobalHelperTest extends TestCase
{
  public function testCleanInput() {
    // Test basic cleaning
    $input    = 'Hello <script>alert("xss")</script> World';
    $expected = 'Hello World';
    $this->assertEquals($expected, cleanInput($input));

    // Test javascript protocol removal (removes protocol only)
    $input    = 'javascript:alert(1)';
    $expected = 'alert(1)';
    $this->assertEquals($expected, cleanInput($input));

    // Test style tag removal
    $input    = '<style>body { display: none; }</style>Visible';
    $expected = 'Visible';
    $this->assertEquals($expected, cleanInput($input));

    // Test empty input
    $this->assertEquals('', cleanInput(''));
  }

  public function testDateInfo() {
    // dateInfo relies on global $LANG, ensuring it's set in bootstrap or here
    global $LANG;
    $LANG = ['some_key' => 'some_value']; // Mock if needed, dateInfo might use keys

    // Test valid date
    $year  = '2023';
    $month = '10';
    $day   = '05';

    $info = dateInfo($year, $month, $day);

    $this->assertIsArray($info);
    // Add more assertions based on expectation of dateInfo structure
    // Assuming it returns year, month, day keys or similar

    // Just verify it doesn't crash and returns array for now
  }
}
