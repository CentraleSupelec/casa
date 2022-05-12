<?php

namespace App\Service;

class ImageUrlService
{
    private string $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getImageBaseUrl(): string
    {
        return $this->baseUrl;
    }
}
