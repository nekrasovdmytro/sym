<?php

namespace App\Libs\Employee\Vacation;

use App\Entity\VacationCalculableEmployeeInterface;

/**
 * Class EmployeeVacationCalculator
 * @package App\Libs\Employee\Vacation
 * @author Dmytro Nekrasov <dmytro.nekrasov@internetstores.com>
 */
class EmployeeVacationCalculator implements EmployeeVacationCalculatorInterface
{
    private const DEFAULT_VACATION_AMOUNT = 26;

    private const AGE_BORDER_TO_INCREASE_VACATION = 30;
    private const WORKING_YEARS_BORDER_TO_INCREASE_VACATION = 5;
    private const INCREASE_VACATION_ON_DAYS = 1;

    /**
     * @var VacationCalculableEmployeeInterface
     */
    protected $employee;
    protected $vacationDays;

    /**
     * @inheritdoc
     *
     * @param VacationCalculableEmployeeInterface $employee
     * @return EmployeeVacationCalculator
     */
    public function setEmployee(VacationCalculableEmployeeInterface $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return EmployeeVacationCalculator
     */
    public function calculate(): self
    {
        $employee = $this->getEmployee();

        if ($employee === null) {
            throw new \RuntimeException('Employee must be set');
        }

        if ($this->hasEmployeeBeganInTheMiddleOfThisYear()) {
            $vacationAmount = $this->calculatePartVacationDays();
        } else {
            $vacationAmount = $this->calculateFullVacationDays();
        }

        $this->setVacationDays($vacationAmount);

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getVacationDays(): int
    {
        if ($this->vacationDays === null) {
            throw new \LogicException('Seems race condition. Method calculate must be called first');
        }

        return $this->vacationDays;
    }

    /**
     * Calculate vacation days for part year
     *
     * @return int
     */
    protected function calculatePartVacationDays(): int
    {
        if ($this->hasEmployeeStartedThisMonth()) {
            return 0;
        }

        return $this->getPartYearVacationDays();
    }

    /**
     * Calculate vacation days for whole year
     *
     * @return int
     */
    protected function calculateFullVacationDays(): int
    {

        $defaultVacationAmount = $this->getDefaultVacationAmount();

        if (
            $this->hasEmployeeAge(self::AGE_BORDER_TO_INCREASE_VACATION) &&
            $this->hasEmployeeWorkedMoreThan(self::WORKING_YEARS_BORDER_TO_INCREASE_VACATION)
        ) {
            $defaultVacationAmount += self::INCREASE_VACATION_ON_DAYS;
        }

        return $defaultVacationAmount;
    }

    /**
     * @return VacationCalculableEmployeeInterface
     */
    public function getEmployee(): ?VacationCalculableEmployeeInterface
    {
        return $this->employee;
    }

    /**
     * @param mixed $vacationDays
     */
    protected function setVacationDays($vacationDays): void
    {
        $this->vacationDays = $vacationDays;
    }

    /**
     * Return true if employee has age more than $age
     *
     * @param int $age
     * @return bool
     */
    protected function hasEmployeeAge(int $age): bool
    {
        $birthday = $this->getEmployee()->getBirthday();
        $now = new \DateTime();

        return $birthday->diff($now)->y >= $age;
    }

    /**
     * Return true if employee worked more than $years
     *
     * @param int $years
     * @return bool
     */
    protected function hasEmployeeWorkedMoreThan(int $years): bool
    {
        $startDay = $this->getEmployee()->getStartDay();
        $now = new \DateTime();

        return $startDay->diff($now)->y >= $years;
    }

    /**
     * Return true if employee started to work this year, but not from the beginning of year
     *
     * @return bool
     */
    protected function hasEmployeeBeganInTheMiddleOfThisYear(): bool
    {
        $startDay = $this->getEmployee()->getStartDay();
        $now = new \DateTime();

        if ((int)$startDay->diff($now)->y !== 0) {
            return false;
        }

        if ((int)$startDay->format('m') === 1) {//first month of the year
            return false;
        }

        return true;
    }

    /**
     * Return true if employee started to work on current month
     *
     * @return bool
     */
    protected function hasEmployeeStartedThisMonth(): bool
    {
        $startDay = $this->getEmployee()->getStartDay();
        $now = new \DateTime();

        if ((int) $startDay->format('y') !== (int) $now->format('y')) {
            return false;
        }

        if ((int) $startDay->format('m') !== (int) $now->format('m')) {
            return false;
        }

        return true;
    }

    /**
     * Get part year vacation days amount for current year
     *
     * @return int
     */
    protected function getPartYearVacationDays(): int
    {
        $defaultVacationAmount = $this->getDefaultVacationAmount();

        $dx = 12;
        $vacationDaysByMonth = ceil($defaultVacationAmount / $dx);

        $startDay = $this->getEmployee()->getStartDay();
        $now = new \DateTime();

        $monthAmount = (int)$startDay->diff($now)->m;

        if ($monthAmount <= 0) {
            return 0;
        }

        return $monthAmount * $vacationDaysByMonth;
    }

    /**
     * Get default vacation amount or contract vacation amount
     *
     * @return int
     */
    private function getDefaultVacationAmount()
    {
        if ($this->getEmployee()->getContractVacationAmount() > 0) {
            return $this->getEmployee()->getContractVacationAmount();
        }

        return self::DEFAULT_VACATION_AMOUNT;
    }
}