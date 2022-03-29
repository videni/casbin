<?php

namespace Videni\Casbin\Acl\Domain;

use Videni\Casbin\Model\ResourceIdentityRetrievalStrategyInterface;
use Videni\Casbin\Util\ClassUtils;

class ResourceIdentityRetrievalStrategy implements ResourceIdentityRetrievalStrategyInterface
{
    public function fromObject(object $object): string
    {
        return ClassUtils::getRealClass($object);
    }
}
