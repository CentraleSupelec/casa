<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use NotFloran\MjmlBundle\Renderer\RendererInterface;

class MjmlRenderer implements RendererInterface
{
    private Client $client;

    public function __construct(string $mjmlBaseUri)
    {
        $this->client = new Client(['base_uri' => $mjmlBaseUri]);
    }

    /**
     * @throws GuzzleException
     */
    public function render(string $mjmlContent): string
    {
        $response = $this->client->post('', [
            'body' => $mjmlContent,
            'headers' => [
                'Content-Type' => 'text/plain',
            ],
        ]);

        return $response->getBody()->getContents();
    }
}
