<?php

namespace Videni\Casbin\Model;

interface ResourceIdentityRetrievalStrategyInterface
{
    public function fromObject(object $object): string;
}
