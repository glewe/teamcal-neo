<?php
namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\AbsenceService;
use App\Models\AbsenceModel;
use App\Models\AllowanceModel;
use App\Models\ConfigModel;
use App\Models\DaynoteModel;
use App\Models\GroupModel;
use App\Models\HolidayModel;
use App\Models\MonthModel;
use App\Models\TemplateModel;
use App\Models\UserGroupModel;
use App\Models\UserModel;

class AbsenceServiceTest extends TestCase
{
  private $AS;
  private $A;
  private $AL;
  private $C;
  private $D;
  private $G;
  private $H;
  private $M;
  private $T;
  private $UG;
  private $U;
  private $UL;
  private $LANG;

  protected function setUp(): void {
    $this->A    = $this->createMock(AbsenceModel::class);
    $this->AL   = $this->createMock(AllowanceModel::class);
    $this->C    = $this->createMock(ConfigModel::class);
    $this->D    = $this->createMock(DaynoteModel::class);
    $this->G    = $this->createMock(GroupModel::class);
    $this->H    = $this->createMock(HolidayModel::class);
    $this->M    = $this->createMock(MonthModel::class);
    $this->T    = $this->createMock(TemplateModel::class);
    $this->UG   = $this->createMock(UserGroupModel::class);
    $this->U    = $this->createMock(UserModel::class);
    $this->UL   = $this->createMock(UserModel::class);
    $this->LANG = [];

    $this->AS = new AbsenceService(
      $this->A,
      $this->AL,
      $this->C,
      $this->D,
      $this->G,
      $this->H,
      $this->M,
      $this->T,
      $this->UG,
      $this->U,
      $this->UL,
      $this->LANG
    );
  }

  public function testAbsenceThresholdReached_Group_ThresholdExceeded() {
    // Inputs
    $year  = '2023';
    $month = '10';
    $day   = '15';
    $base  = 'group';
    $group = '1';

    // Mock Config: Threshold is 50%
    $this->C->method('read')->with('declThreshold')->willReturn('50');

    // Mock UserGroupModel
    // Assume 10 users in group
    $this->UG->method('countMembers')->with($group)->willReturn(10);

    // Members list (only username needed for the loop)
    $members = [];
    for ($i = 0; $i < 10; $i++) {
      $members[] = ['username' => 'user' . $i];
    }
    $this->UG->method('getAllForGroup')->with($group)->willReturn($members);

    // Mock TemplateModel
    // Assume 5 users already absent. +1 requested = 6. 6/10 = 60% > 50%.
    // countAllAbsences called 10 times. Return 1 for first 5 users, 0 for others.
    // Or simplified: just return 1 for half calls. 
    // Logic in service: foreach member, absences += countAllAbsences(...)

    $matcher = $this->exactly(10);
    $this->T->expects($matcher)
      ->method('countAllAbsences')
      ->willReturnCallback(function () use ($matcher) {
        // Logic to return 1 for first 5 calls (mocking 5 absences)
        static $count = 0;
        $count++;
        return ($count <= 5) ? 1 : 0;
      });

    $result = $this->AS->absenceThresholdReached($year, $month, $day, $base, $group);

    // Calculation: 
    // Existing Absences = 5
    // New Requested = 1 (implicit in logic `$absences++`)
    // Total = 6
    // Rate = 6 / 10 * 100 = 60%
    // Threshold = 50%
    // 60 >= 50 => True

    $this->assertTrue($result, 'Threshold should be reached (60% vs 50%)');
  }

  public function testAbsenceThresholdReached_Group_ThresholdNotReached() {
    // Inputs
    $year  = '2023';
    $month = '10';
    $day   = '15';
    $base  = 'group';
    $group = '1';

    // Mock Config: Threshold is 80%
    $this->C->method('read')->with('declThreshold')->willReturn('80');

    // Mock UserGroupModel
    $this->UG->method('countMembers')->willReturn(10);
    $members = array_fill(0, 10, ['username' => 'u']);
    $this->UG->method('getAllForGroup')->willReturn($members);

    // Mock Template: 2 existing absences + 1 new = 3. 3/10 = 30%. < 80%.
    $this->T->method('countAllAbsences')->willReturnOnConsecutiveCalls(1, 1, 0, 0, 0, 0, 0, 0, 0, 0);

    $result = $this->AS->absenceThresholdReached($year, $month, $day, $base, $group);

    $this->assertFalse($result, 'Threshold should NOT be reached (30% vs 80%)');
  }
}
