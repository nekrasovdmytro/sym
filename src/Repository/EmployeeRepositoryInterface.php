<?php

namespace App\Repository;

use App\Entity\VacationCalculableEmployeeInterface;

/**
 * Interface EmployeeRepositoryInterface
 * @package App\Repository
 * @author Dmytro Nekrasov <dmytro.nekrasov@internetstores.com>
 */
interface EmployeeRepositoryInterface
{
    /**
     * @return VacationCalculableEmployeeInterface[]
     */
    public function findAllEmployees();
}