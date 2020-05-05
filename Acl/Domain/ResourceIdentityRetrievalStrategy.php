<?php

namespace Videni\Bundle\CasbinBundle\Acl\Domain;

use Videni\Bundle\CasbinBundle\Model\ResourceIdentityRetrievalStrategyInterface;
use Videni\Bundle\CasbinBundle\Util\ClassUtils;

class ResourceIdentityRetrievalStrategy implements ResourceIdentityRetrievalStrategyInterface
{
    public function fromObject(object $object): string
    {
        return ClassUtils::getRealClass($object);
    }
}
