<?php
namespace Libs\Employee\Vacation;

use App\Entity\Employee;
use App\Entity\VacationCalculableEmployeeInterface;
use App\Libs\Employee\Vacation\EmployeeVacationCalculator;
use Codeception\Stub;

class EmployeeVacationCalculatorTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var EmployeeVacationCalculator
     */
    protected $employeeVacationCalculator;
    
    protected function _before()
    {
        $this->employeeVacationCalculator = new EmployeeVacationCalculator();
    }

    protected function _after()
    {
    }

    // tests
    public function testHasEmployeeAge()
    {
        $employee = self::make(
            Employee::class,
            ['birthday' => new \DateTime('1980-10-10')]
        );

        $this->employeeVacationCalculator->setEmployee($employee);
        $method = $this->tester->getMethod(EmployeeVacationCalculator::class, 'hasEmployeeAge');

        $this->assertTrue($method->invokeArgs($this->employeeVacationCalculator, [30]));
        $this->assertFalse($method->invokeArgs($this->employeeVacationCalculator, [50]));
    }

    public function testHasEmployeeWorkedMoreThan()
    {
        $employee = self::make(
            Employee::class,
            ['startDay' => new \DateTime('2015-10-10')]
        );

        $this->employeeVacationCalculator->setEmployee($employee);
        $method = $this->tester->getMethod(EmployeeVacationCalculator::class, 'hasEmployeeWorkedMoreThan');

        $this->assertFalse($method->invokeArgs($this->employeeVacationCalculator, [5]));
        $this->assertTrue($method->invokeArgs($this->employeeVacationCalculator, [2]));
    }

    public function testHasEmployeeWorkedLessThanOneYear()
    {
        $employee = self::make(
            Employee::class,
            ['startDay' => new \DateTime()]
        );

        $this->employeeVacationCalculator->setEmployee($employee);
        $method = $this->tester->getMethod(EmployeeVacationCalculator::class, 'hasEmployeeBeganInTheMiddleOfThisYear');

        $this->assertTrue($method->invokeArgs($this->employeeVacationCalculator, []));
    }

    public function testHasEmployeeStartedThisMonth()
    {
        $employee = self::make(
            Employee::class,
            ['startDay' => new \DateTime()]
        );

        $this->employeeVacationCalculator->setEmployee($employee);
        $method = $this->tester->getMethod(EmployeeVacationCalculator::class, 'hasEmployeeStartedThisMonth');

        $this->assertTrue($method->invokeArgs($this->employeeVacationCalculator, []));
    }

    public function testGetPartYearVacationDays()
    {
        $now = new \DateTime();
        $now->setDate((int)$now->format('Y'), (int)$now->format('m'), 1);
        $now->modify('+4 months');
        $employee = self::make(
            Employee::class,
            ['startDay' => $now]
        );

        $this->employeeVacationCalculator->setEmployee($employee);
        $method = $this->tester->getMethod(EmployeeVacationCalculator::class, 'getPartYearVacationDays');

        echo $method->invokeArgs($this->employeeVacationCalculator, []);

        $expected = ceil(26 / 12 * 4);
        $this->assertEquals($expected, $method->invokeArgs($this->employeeVacationCalculator, []));
    }

    public function testCalculatePartVacationDays()
    {
        $now = new \DateTime();
        $now->modify('+4 months');
        $employee = self::make(
            Employee::class,
            ['startDay' => $now]
        );

        $this->employeeVacationCalculator->setEmployee($employee);
        $method = $this->tester->getMethod(EmployeeVacationCalculator::class, 'calculatePartVacationDays');

        $expected = ceil(26 / 12) * 4;
        $this->assertEquals($expected, $method->invokeArgs($this->employeeVacationCalculator, []));
    }

    public function testCalculatePartVacationDaysEmployeeStartedThisMonth()
    {
        $now = new \DateTime();
        $employee = self::make(
            Employee::class,
            ['startDay' => $now]
        );

        $this->employeeVacationCalculator->setEmployee($employee);
        $method = $this->tester->getMethod(EmployeeVacationCalculator::class, 'calculatePartVacationDays');

        $this->assertEquals(0, $method->invokeArgs($this->employeeVacationCalculator, []));
    }

    public function testCalculateFullVacationDays()
    {
        $employee = self::make(
            Employee::class,
            [
                'startDay' => new \DateTime('2014-10-10'),
                'birthday' => new \DateTime('1991-10-10')
            ]
        );

        $this->employeeVacationCalculator->setEmployee($employee);
        $method = $this->tester->getMethod(EmployeeVacationCalculator::class, 'calculateFullVacationDays');

        $this->assertEquals(26, $method->invokeArgs($this->employeeVacationCalculator, []));
    }

    public function testCalculateFullVacationDaysWithSpecialContract()
    {
        $employee = self::make(
            Employee::class,
            [
                'startDay' => new \DateTime('2014-10-10'),
                'birthday' => new \DateTime('1991-10-10'),
                'contractVacationAmount' => 100
            ]
        );

        $this->employeeVacationCalculator->setEmployee($employee);
        $method = $this->tester->getMethod(EmployeeVacationCalculator::class, 'calculateFullVacationDays');

        $this->assertEquals(100, $method->invokeArgs($this->employeeVacationCalculator, []));
    }

    public function testCalculateFullVacationDaysWithSpecialContractAndAgeMore30Years()
    {
        $employee = self::make(
            Employee::class,
            [
                'startDay' => new \DateTime('2009-10-10'),
                'birthday' => new \DateTime('1981-10-10'),
                'contractVacationAmount' => 100
            ]
        );

        $this->employeeVacationCalculator->setEmployee($employee);
        $method = $this->tester->getMethod(EmployeeVacationCalculator::class, 'calculateFullVacationDays');

        $this->assertGreaterThan(100, $method->invokeArgs($this->employeeVacationCalculator, []));
    }

    public function testCalculateByEmployee()
    {
        $employee = self::make(
            Employee::class,
            [
                'startDay' => new \DateTime('2009-10-10'),
                'birthday' => new \DateTime('1981-10-10'),
                'contractVacationAmount' => 100
            ]
        );

        $this->employeeVacationCalculator->setEmployee($employee);
        $method = $this->tester->getMethod(EmployeeVacationCalculator::class, 'calculate');

        $this->assertGreaterThan(100, $method->invokeArgs($this->employeeVacationCalculator, [])->getVacationDays());
    }
}