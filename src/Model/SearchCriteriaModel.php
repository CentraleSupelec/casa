<?php

namespace App\Model;

class SearchCriteriaModel
{
    protected ?int $maxPrice = null;

    protected ?int $minArea = null;

    protected int $maxResultsByPage = 15;

    protected bool $accessibility = false;

    protected ?string $city = null;

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
}
