<?php

namespace App\Model;

class SearchCriteriaModel
{
    protected ?int $maxPrice = null;

    protected ?int $minArea = null;

    protected int $maxResultsByPage = 15;

    protected bool $accessibility = false;

    /**
     * Get the value of maxPrice.
     */
    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    /**
     * Set the value of maxPrice.
     *
     * @return self
     */
    public function setMaxPrice(?int $maxPrice)
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    /**
     * Get the value of minArea.
     */
    public function getMinArea(): ?int
    {
        return $this->minArea;
    }

    /**
     * Set the value of minArea.
     *
     * @return self
     */
    public function setMinArea(?int $minArea)
    {
        $this->minArea = $minArea;

        return $this;
    }

    /**
     * Get the value of maxResults.
     */
    public function getMaxResultsByPage(): int
    {
        return $this->maxResultsByPage;
    }

    /**
     * Set the value of maxResults.
     *
     * @return self
     */
    public function setMaxResultsByPage($max)
    {
        $this->maxResultsByPage = $max;

        return $this;
    }

    /**
     * Get the value of accessibilty.
     */
    public function getAccessibility(): bool
    {
        return $this->accessibility;
    }

    /**
     * Set the value of accessibilty.
     *
     * @return self
     */
    public function setAccessibility(bool $accessibility)
    {
        $this->accessibility = $accessibility;

        return $this;
    }
}
