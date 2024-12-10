<?php

namespace PingPong\Models;

use GuzzleHttp\Exception\GuzzleException;
use PingPong\HttpClient\HttpClient;

require '../vendor/autoload.php';

class Models
{
    private HttpClient $client;

    function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    private function modelFactory(array $data): Model
    {
        return new Model(
            $data['id'] ?? '',
            $data['name'] ?? '',
            $data['description'] ?? 'No description available',
            $data['args'] ?? ''
        );
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function list(): array
    {
        $response = $this->client->get('/api/v1/models');
        if (is_array($response) && isset($response[0]) && is_array($response[0])) {
            $models = [];
            foreach ($response as $modelData) {
                $missingFields = [];
                foreach (['id', 'name', 'description', 'args'] as $field) {
                    if (!isset($modelData[$field])) {
                        $missingFields[] = $field;
                    }
                }
                if (!empty($missingFields)) {
                    echo "Skipping model due to missing fields: " . implode(', ', $missingFields) . "\n";
                    continue;
                }
                $models[] = $this->modelFactory($modelData);
            }
            return $models;
        }
        throw new \UnexpectedValueException('Invalid response format: Expected an array of models');
    }

    /**
     * @throws GuzzleException
     */
    public function getById(string $id): Model
    {
        $response = $this->client->get('/api/v1/models/' . $id);

        return $this->modelFactory($response);
    }
    public function getByName(string $name): Model
    {
        $response = $this->client->get('/api/v1/models/name/'.$name);
        return $this->modelFactory($response);
    }
}
