<?php

namespace Videni\Casbin\Adapter;

use CasbinAdapter\Database\Adapter;
use TechOne\Database\Manager;

class DatabaseAdapter extends Adapter
{
    /**
     * Disable loading database migration every time, 
     * we prefer to generate database for the first time.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->filtered = false;
        $this->connection = (new Manager($config))->getConnection();
    }
}