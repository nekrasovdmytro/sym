<?php

namespace App\Entity;

/**
 * Interface VacationCalculableEmployeeInterface
 * @package App\Entity
 * @author Dmytro Nekrasov <dmytro.nekrasov@internetstores.com>
 */
interface VacationCalculableEmployeeInterface
{
    public function getFirstName(): ?string;
    public function getLastName(): ?string;

    public function getStartDay(): ?\DateTimeInterface;
    public function getBirthday(): ?\DateTimeInterface;
    public function getContractVacationAmount(): ?int;
    public function getEmployeeVacationAmountPerYear(): ?int;
}