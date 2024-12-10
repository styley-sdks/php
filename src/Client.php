<?php

namespace PingPong;

use PingPong\Deployments\Deployments;
use PingPong\HttpClient\HttpClient;
use PingPong\Models\Models;

require '../vendor/autoload.php';

class Client
{
    public Deployments $deployments;
    public Models $models;
    private string $apiKey;

    private string $mmBaseUrl;
    
    private HttpClient $client;

    public function __construct(string $apiKey = "")
    {
        if ($apiKey === "") {
            $apiKey = getenv('X_STYLEY_KEY');
        }

        $mmBaseUrl = getenv('MM_HOST_URL');
        if ($mmBaseUrl === false || $mmBaseUrl === "") {
            $mmBaseUrl = "https://api-qa.mediamagic.ai";
        }
            
        $this->client = new HttpClient($apiKey, $mmBaseUrl);
        $this->deployments = new Deployments($this->client);
        $this->models = new Models($this->client);
    }

    public function deployments(): Deployments
    {
        return $this->deployments;
    }

    public function models(): Models
    {
        return $this->models;
    }
}