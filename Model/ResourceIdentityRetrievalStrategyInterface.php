<?php

namespace Videni\Bundle\CasbinBundle\Model;

interface ResourceIdentityRetrievalStrategyInterface
{
    public function fromObject(object $object): string;
}
