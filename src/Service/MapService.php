<?php

namespace App\Service;

use App\Entity\Coordinates;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MapService
{
    public function __construct(
        private HttpClientInterface $client,
        private string $apiKey,
        private string $serviceUrl,
        private LoggerInterface $logger)
    {
    }

    public function getCoordinatesFromAddress(string $address): Coordinates
    {
        $result = new Coordinates();

        try {
            $reponse = $this->client->request('GET', $this->serviceUrl, ['query' => ['text' => $address, 'apiKey' => $this->apiKey]]);
            $coordinates = json_decode($reponse->getContent())->features[0]->geometry->coordinates;
        } catch (Exception $exception) {
            $this->logger->error('Geocoding '.$address.' failed: '.$exception->getMessage());
            $coordinates = [null, null];
        }

        $result->setLatitude($coordinates[1]);
        $result->setLongitude($coordinates[0]);

        return $result;
    }
}
