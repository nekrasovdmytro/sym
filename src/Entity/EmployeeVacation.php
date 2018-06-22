<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeVacationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class EmployeeVacation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Employee", inversedBy="employeeVacation", cascade={"persist", "remove"})
     */
    private $employee;

    /**
     * @ORM\Column(type="integer")
     */
    private $amountPerYear;

    /**
     * @ORM\Column(type="integer")
     */
    private $amountUsed = 0;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateDate;

    public function getId()
    {
        return $this->id;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getAmountPerYear(): ?int
    {
        return $this->amountPerYear;
    }

    public function setAmountPerYear(int $amountPerYear): self
    {
        $this->amountPerYear = $amountPerYear;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(\DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    public function getAmountUsed(): ?int
    {
        return $this->amountUsed;
    }

    public function setAmountUsed(int $amountUsed): self
    {
        $this->amountUsed = $amountUsed;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function beforePersist(): void
    {
        $this->updateDate = new \DateTime();
    }
}
