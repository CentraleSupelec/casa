<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class SearchCriteriaModel
{
    #[Assert\GreaterThan(0)]
    private ?int $maxPrice = null;

    #[Assert\GreaterThan(0)]
    private ?int $minArea = null;

    private int $maxResultsByPage = 15;

    private bool $accessibility = false;

    private bool $aplAgreement = false;

    private ?string $city = null;

    private array $stayDurations = [];

    private array $occupationModes = [];

    private array $leaseType = [];

    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    public function setMaxPrice(?int $maxPrice): self
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    public function getMinArea(): ?int
    {
        return $this->minArea;
    }

    public function setMinArea(?int $minArea): self
    {
        $this->minArea = $minArea;

        return $this;
    }

    public function getMaxResultsByPage(): int
    {
        return $this->maxResultsByPage;
    }

    public function setMaxResultsByPage($max): self
    {
        $this->maxResultsByPage = $max;

        return $this;
    }

    public function getAccessibility(): bool
    {
        return $this->accessibility;
    }

    public function setAccessibility(bool $accessibility): self
    {
        $this->accessibility = $accessibility;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get the value of stayDurations.
     */
    public function getStayDurations(): array
    {
        return $this->stayDurations;
    }

    /**
     * Set the value of stayDurations.
     *
     * @return self
     */
    public function setStayDurations($stayDurations)
    {
        $this->stayDurations = $stayDurations;

        return $this;
    }

    /**
     * Get the value of stayDurations.
     */
    public function getOccupationModes(): array
    {
        return $this->occupationModes;
    }

    /**
     * Set the value of stayDurations.
     *
     * @return self
     */
    public function setOccupationModes(array $occupationModes)
    {
        $this->occupationModes = $occupationModes;

        return $this;
    }

    /**
     * Get the value of aplAgreement.
     */
    public function getAplAgreement(): bool
    {
        return $this->aplAgreement;
    }

    /**
     * Set the value of aplAgreement.
     *
     * @return self
     */
    public function setAplAgreement(bool $aplAgreement)
    {
        $this->aplAgreement = $aplAgreement;

        return $this;
    }

    /**
     * Get the value of leaseType.
     */
    public function getLeaseType(): array
    {
        return $this->leaseType;
    }

    /**
     * Set the value of leaseType.
     *
     * @return self
     */
    public function setLeaseType($leaseType)
    {
        $this->leaseType = $leaseType;

        return $this;
    }
}
