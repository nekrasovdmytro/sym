<?php

namespace App\Repository;

use App\Entity\EmployeeVacation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EmployeeVacation|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmployeeVacation|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmployeeVacation[]    findAll()
 * @method EmployeeVacation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeVacationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EmployeeVacation::class);
    }
}
