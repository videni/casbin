<?php

namespace Videni\Casbin;

use Videni\Casbin\Exception\NotFoundException;
use Casbin\Enforcer;
use Psr\Container\ContainerInterface;

class EnforcerManager
{
    private $enforcers = [];

    private $defaultEnforcer;
    
    private $container;

    public function __construct(
        $defaultEnforcer,
        array $enforcers,
        ContainerInterface $container
    ) {
        $this->defaultEnforcer = $defaultEnforcer;
        $this->enforcers = $enforcers;
        $this->container = $container;
    }

    public function getEnforcer($name = null): Enforcer
    {
        $name = $name ?? $this->defaultEnforcer;
        if (!isset($this->enforcers[$name])) {
            throw new NotFoundException(sprintf('Enforcer %s is not set', $name));
        }

        return $this->container->get($this->enforcers[$name]);
    }
}
