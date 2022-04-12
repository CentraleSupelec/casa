<?php

namespace App\Service;

class ImageUrlService
{
    private $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getImageBaseUrl(): string
    {
        return $this->baseUrl;
    }
}
