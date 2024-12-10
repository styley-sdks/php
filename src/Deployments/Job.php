<?php

namespace PingPong\Deployments;

class Job
{
    public string $id;
    public string $status;
    public string $deploymentId;
    public array $args;
    public array $files;
    public string $createdAt;
    public float $creditsUsed;
    public int $eta;

    public function __construct(
        ?string $id = null,
        ?string $status = null,
        ?string $deploymentId = null,
        ?array $args = null,
        ?array $files = null,
        ?string $createdAt = null,
        ?float $creditsUsed = null,
        ?int $eta = null
    ) {
        $this->id = $id ?? '';
        $this->status = $status ?? '';
        $this->deploymentId = $deploymentId ?? '';
        $this->args = $args ?? [];
        $this->files = $files ?? [];
        $this->createdAt = $createdAt ?? '';
        $this->creditsUsed = $creditsUsed ?? 0.0;
        $this->eta = $eta ?? 0;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function setFiles(array $files)
    {
        $this->files = $files;
    }

    public function getCreditsUsed(): int
    {
        return $this->creditsUsed;
    }

    public function setCreditsUsed(int $creditsUsed)
    {
        $this->creditsUsed = $creditsUsed;
    }

    public function getEta(): int
    {
        return $this->eta;
    }

    public function setEta(int $eta)
    {
        $this->eta = $eta;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }
}