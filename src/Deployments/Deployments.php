<?php

namespace PingPong\Deployments;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use PingPong\HttpClient\HttpClient;

require '../vendor/autoload.php';

const COMPLETE = 'complete';
const FAILED = 'failed';
const ETA = 180;

class Deployments
{
    private HttpClient $client;

    function __construct(HttpClient $client)
    {   
        $this->client = $client;
    }

    /**
     * @throws Exception
     */
    private function deploymentFactory(array $response): Deployment
    {
        $j = $response['job'];

        if (empty($j)) {
            throw new Exception('Job is empty');
        }

        $job = new Job(
            id: $j['id'] ?? '',
            status: $j['status'] ?? '',
            deploymentId: $j['deployment_id'] ?? '',
            args: $j['args'] ?? [],
            files: $j['files'] ?? [],
            createdAt: $j['created_at'] ?? '',
            creditsUsed: (float)($j['credits_used'] ?? 0),
            eta: ($j['eta'] ?? 0) 
        );

        // Note: Ensure 'status' is passed as a string
        return new Deployment(
            $response['name'],          // Deployment name as string
            $response['model_id'],      // Model ID as string
            $response['status'],        // Deployment status as string
            $job,                       // Job object
            $response['job_id']         // Job ID as string
        );
    }

    /**
     * @throws Exception|GuzzleException
     */
    public function create(DeploymentInput $deploymentInput): Deployment
    {
        try {
            $responseData = $this->client->post("/api/v1/deployments", $deploymentInput);


            $deployment = new Deployment(
                $responseData['name'],
                $responseData['model_id'],
                $responseData['status'],
                new Job(
                    id: $responseData['job']['id'] ?? '',
                    status: $responseData['job']['status'] ?? '',
                    deploymentId: $responseData['job']['deployment_id'] ?? '',
                    args: $responseData['job']['args'] ?? [],
                    files: $responseData['job']['files'] ?? [],
                    createdAt: $responseData['job']['created_at'] ?? '',
                    creditsUsed: (float)($responseData['job']['credits_used'] ?? 0),
                    eta: $responseData['job']['eta'] ?? 0 
                ),
                $responseData['job_id'],
            );
            
            if (isset($response['job']['eta']) && $response['job']['eta'] !== null) {
                $eta = $response['job']['eta'];
            } else {
                $eta = ETA;
            }

            $status = $deployment->getStatus();
            $jobId = $responseData['job_id'];

            while ($status !== COMPLETE && $status !== FAILED && $eta > 0) {
                sleep(10);
                $jobData = $this->client->get("/api/v1/jobs/" . $jobId);

                $job = new Job(
                    $jobData['files'] ?? null,
                    $jobData['credits_used'] ?? 0,
                    $jobData['eta'] ?? 0,
                    $jobData['status'] ?? ""
                );

                $status = $jobData['status'];
                $deployment->setJob($job);
                $deployment->setStatus($status);
                $eta -= 5;
            }
            return $deployment;
        } catch (GuzzleException $e) {
            throw new Exception('HTTP request failed: ' . $e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            throw new Exception('An error occurred: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function getById(string $id): Deployment
    {
        $response = $this->client->get('/api/v1/deployments/' . $id);
        return $this->deploymentFactory($response);
    }

    public function getJob(string $id): Job
    {
        $response = $this->client->get('/api/v1/jobs/' . $id);
        
        $job = new Job(
            id: $response['id'] ?? '',
            status: $response['status'] ?? '',
            deploymentId: $response['deployment_id'] ?? '',
            args: $response['args'] ?? [],
            files: $response['files'] ?? [],
            createdAt: $response['created_at'] ?? '',
            creditsUsed: (float)($response['credits_used'] ?? 0),
            eta: ($response['eta'] ?? 0) 
        );
    
        return $job; 
    }    
}