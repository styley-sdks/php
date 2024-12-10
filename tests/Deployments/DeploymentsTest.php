<?php

use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use PingPong\Deployments\Deployment;
use PingPong\Deployments\Deployments;
use PingPong\Deployments\DeploymentInput;
use PingPong\HttpClient\HttpClient;

class DeploymentsTest extends TestCase
{
    public function testCreate()
    {
        $httpClient = $this->createMock(HttpClient::class);
        $deploymentInput = new DeploymentInput('deployment_name', 'model_id', []);
        $response = [
            'name' => 'deployment_name',
            'model_id' => 'model_id',
            'status' => 'complete', 
            'job' => [
                'files' => ['test.jpg'],
                'credits_used' => 1,
            ],
        ];
        

        $httpClient
            ->expects($this->once())
            ->method('post')
            ->with('/api/v1/deployments', $deploymentInput)
            ->willReturn($response);

        $deployments = new Deployments($httpClient);
        $deployment = $deployments->create($deploymentInput);

        $this->assertInstanceOf(Deployment::class, $deployment);
        $this->assertEquals('deployment_name', $deployment->name);
        $this->assertEquals('model_id', $deployment->modelId);
        $this->assertEquals('test.jpg', $deployment->job->files[0]);
        $this->assertEquals(1, $deployment->job->creditsUsed);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetById()
    {
        $httpClient = $this->createMock(HttpClient::class);
        $deploymentId = 'deployment_id';
        $response = [
            'name' => 'model_name',
            'model_id' => 'model_id',
            'status' => 'complete',  
            'job' => [
                'files' => ['test.jpg'],
                'credits_used' => 1,
            ],
        ];        

        $httpClient
            ->expects($this->once())
            ->method('get')
            ->with('/api/v1/deployments/' . $deploymentId)
            ->willReturn($response);

        $deployments = new Deployments($httpClient);
        $deployment = $deployments->getById($deploymentId);

        $this->assertInstanceOf(Deployment::class, $deployment);
        $this->assertEquals('model_name', $deployment->name);
        $this->assertEquals('model_id', $deployment->modelId);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetJobById()
    {
        $httpClient = $this->createMock(HttpClient::class);
        $jobId = 'job_id';
        $response = [
            'id' => 'job_id',
            'status' => 'complete',
            'deployment_id' => 'test_deployment_id',
            'files' => [
                'https://cdn.mediamagic.dev/media/5ae33a02-9cf9-11ef-9057-30d042e69440.json',
            ],
            'credits_used' => 0.4,
            'eta' => 0,
        ];

        $httpClient
            ->expects($this->once())
            ->method('get')
            ->with('/api/v1/jobs/' . $jobId)
            ->willReturn($response);

        $job = new Deployments($httpClient);
        $fetchedJob = $job->getJob($jobId);

        $this->assertEquals('complete', $fetchedJob->status);
        $this->assertEquals('https://cdn.mediamagic.dev/media/5ae33a02-9cf9-11ef-9057-30d042e69440.json', $fetchedJob->files[0]);
        $this->assertEquals('test_deployment_id', $fetchedJob->deploymentId);
        $this->assertEquals(0.4, $fetchedJob->creditsUsed);
        $this->assertEquals(0, $fetchedJob->eta);
    }

}
