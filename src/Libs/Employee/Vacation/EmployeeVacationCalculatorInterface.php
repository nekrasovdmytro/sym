<?php

namespace App\Libs\Employee\Vacation;

use App\Entity\VacationCalculableEmployeeInterface;

/**
 * Interface EmployeeVacationCalculatorInterface
 * @package App\Libs\Employee\Vacation
 * @author Dmytro Nekrasov <dmytro.nekrasov@internetstores.com>
 */
interface EmployeeVacationCalculatorInterface
{

    /**
     * Set employee implemented with VacationCalculableEmployeeInterface
     *
     * @param VacationCalculableEmployeeInterface $employee
     * @return mixed
     */
    public function setEmployee(VacationCalculableEmployeeInterface $employee);

    /**
     * Calculate vacation days for employee
     *
     * @return mixed
     */
    public function calculate();

    /**
     * Return amount of vacation days
     *
     * @return int
     */
    public function getVacationDays(): int;
}