<?php

namespace App\Libs;

use App\Entity\Employee;
use App\Entity\EmployeeVacation;
use App\Libs\Employee\Vacation\EmployeeVacationCalculatorInterface;
use App\Libs\IO\OutputFileInterface;
use App\Repository\EmployeeRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class EmployeeVacationService
 * @package App\Libs
 * @author Dmytro Nekrasov <dmytro.nekrasov@internetstores.com>
 */
class EmployeeVacationService
{
    /**
     * @var EmployeeVacationCalculatorInterface
     */
    protected $employeeVacationCalculator;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var OutputFileInterface
     */
    protected $output;

    public function __construct(
        EntityManagerInterface $entityManager,
        EmployeeVacationCalculatorInterface $employeeVacationCalculator,
        OutputFileInterface $output

    )
    {
        $this->entityManager = $entityManager;
        $this->employeeVacationCalculator = $employeeVacationCalculator;
        $this->output = $output;
    }

    public function determineEmployeeVacationDays(int $year): void
    {
        $updatedEmployeeList = $this->updateEmployeeVacationDays();
        $this->outputIntoFilePerYear($year, $updatedEmployeeList);
    }

    protected function updateEmployeeVacationDays(): \Generator
    {
        $this->getEntityManager()->beginTransaction();

        try {
            foreach ($this->getCalculatedVacationEmployees() as $employee) {
                $this->getEntityManager()->persist($employee);
            }

            $this->getEntityManager()->flush();
            $this->getEntityManager()->commit();

        } catch (\Exception $e) {
            $this->getEntityManager()->rollback();

            throw new \RuntimeException($e->getMessage());
        }

        return $this->getCalculatedVacationEmployees();
    }

    protected function outputIntoFilePerYear($year, \Traversable $employeeList): void
    {
        $this->output->setFilePrefix($year);
        $this->output->setData($employeeList);
        $this->output->output();
    }

    protected function &getCalculatedVacationEmployees(): \Generator
    {
        /**
         * @var Employee[] $employees
         */
        $employees = $this->getEmployeeRepository()->findAllEmployees();

        if ($employees === null) {
            yield [];
        }

        foreach ($employees as $employee) {
            $vacationDays = $this->getEmployeeVacationCalculator()
                ->setEmployee($employee)
                ->calculate()
                ->getVacationDays();


            $employeeVacation = $employee->getEmployeeVacation() ?? new EmployeeVacation();
            $employeeVacation->setAmountPerYear($vacationDays);

            $employee->setEmployeeVacation($employeeVacation);

            yield $employee;
        }
    }

    /**
     * @return EmployeeRepositoryInterface
     */
    public function getEmployeeRepository(): EmployeeRepositoryInterface
    {
        $repository = $this->getEntityManager()->getRepository(Employee::class);

        if (!($repository instanceof EmployeeRepositoryInterface)) {
            throw new \LogicException('Repository must be implemented with EmployeeRepositoryInterface');
        }

        return $repository;
    }

    /**
     * @return EmployeeVacationCalculatorInterface
     */
    public function getEmployeeVacationCalculator(): EmployeeVacationCalculatorInterface
    {
        return $this->employeeVacationCalculator;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}