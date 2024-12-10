<?php

namespace PingPong\Models;

class Model {
    public readonly string $id;
    public readonly string $name;
    public readonly string $description;
    public readonly array $args;

    function __construct(
        string $id,
        string $name,
        string $description,
        array $args
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->args = $args;
    }
}
