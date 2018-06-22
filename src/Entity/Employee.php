<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeRepository")
 */
class Employee implements VacationCalculableEmployeeInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $lastName;

    /**
     * @ORM\Column(type="date")
     */
    private $birthday;

    /**
     * @ORM\Column(type="date")
     */
    private $startDay;

    /**
     * @ORM\Column(type="integer")
     */
    private $contractVacationAmount;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\EmployeeVacation", mappedBy="employee", cascade={"persist", "remove"})
     */
    private $employeeVacation;

    public function getId()
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getStartDay(): ?\DateTimeInterface
    {
        return $this->startDay;
    }

    public function setStartDay(\DateTimeInterface $startDay): self
    {
        $this->startDay = $startDay;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getContractVacationAmount(): ?int
    {
        return $this->contractVacationAmount;
    }

    public function setContractVacationAmount(int $contractVacationAmount): self
    {
        $this->contractVacationAmount = $contractVacationAmount;

        return $this;
    }

    public function getEmployeeVacation(): ?EmployeeVacation
    {
        return $this->employeeVacation;
    }

    public function setEmployeeVacation(?EmployeeVacation $employeeVacation): self
    {
        $this->employeeVacation = $employeeVacation;

        // set (or unset) the owning side of the relation if necessary
        $newEmployee = $employeeVacation === null ? null : $this;
        if ($newEmployee !== $employeeVacation->getEmployee()) {
            $employeeVacation->setEmployee($newEmployee);
        }

        return $this;
    }

    public function getEmployeeVacationAmountPerYear(): ?int
    {
        if ($this->getEmployeeVacation() === null) {
            return null;
        }

        return $this->getEmployeeVacation()->getAmountPerYear();
    }
}
