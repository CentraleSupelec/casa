<?php

namespace App\Model;

class SearchCriteriaModel
{
    protected $maxPrice;

    protected $minArea;

    protected $maxResultsByPage = 15;

    /**
     * Get the value of maxPrice.
     */
    public function getMaxPrice()
    {
        return $this->maxPrice;
    }

    /**
     * Set the value of maxPrice.
     *
     * @return self
     */
    public function setMaxPrice($maxPrice)
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    /**
     * Get the value of minArea.
     */
    public function getMinArea()
    {
        return $this->minArea;
    }

    /**
     * Set the value of minArea.
     *
     * @return self
     */
    public function setMinArea($minArea)
    {
        $this->minArea = $minArea;

        return $this;
    }

    /**
     * Get the value of maxResults.
     */
    public function getMaxResultsByPage()
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
}
