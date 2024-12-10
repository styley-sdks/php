<?php

namespace PingPong\Deployments;

class Deployment
{
    public string $name;
    public string $modelId;
    public string $status;
    public ?Job $job;
    public ?string $jobId;

    public function __construct(
        string $name = '',
        string $modelId = '',
        string $status = '',
        ?Job $job = null,
        ?string $jobId = null
    ) {
        $this->name = $name;
        $this->modelId = $modelId;
        $this->status = $status;
        $this->job = $job;
        $this->jobId = $jobId;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function getModelId(): string
    {
        return $this->modelId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getJob(): Job
    {
        return $this->job;
    }

    public function getJobId(): string
    {
        return $this->jobId;
    }

    public function setJob(Job $job)
    {
        $this->job = $job;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }
}