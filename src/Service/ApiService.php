<?php

namespace App\Service;

use Exception;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiService
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function fetchDataFromApi($apiUrl): array
    {
        try {
            $response = $this->httpClient->request('GET', $apiUrl);
            return $response->toArray();
        } catch (Exception $e) {
            throw new Exception('Error communicating with the API: ' . $e->getMessage());
        }
    }
}
