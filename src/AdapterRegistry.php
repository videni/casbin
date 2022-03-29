<?php

namespace Videni\Casbin;

class AdapterRegistry
{
    private $adapters = [];
    
    public function __construct(array $adapters = [])
    {
        $this->adapters = $adapters;
    }

    public function all(): array
    {
        return $this->adapters;
    }
}