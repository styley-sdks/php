<?php

namespace PingPong\Deployments;

use \PingPong\HttpClient\Mappable;


class DeploymentInput implements Mappable
{
    public function __construct(
        public string $name,
        public string $model_id,
        public array $args
    ) {}

    public function toMap(): array
    {
        return [
            'name' => $this->name,
            'model_id' => $this->model_id,
            'args' => $this->args,
        ];
    }
}
