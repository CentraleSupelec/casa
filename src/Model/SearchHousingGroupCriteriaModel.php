<?php

namespace App\Model;

class SearchHousingGroupCriteriaModel
{
    private int $maxResultsByPage = 15;

    private ?string $city = null;

    public function getMaxResultsByPage(): int
    {
        return $this->maxResultsByPage;
    }

    public function setMaxResultsByPage($max): self
    {
        $this->maxResultsByPage = $max;

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
