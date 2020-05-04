<?php

namespace Videni\Bundle\CasbinBundle\Model;

use Videni\Bundle\CasbinBundle\Exception\InvalidDomainObjectException;
use Videni\Bundle\CasbinBundle\Model\ObjectIdentityRetrievalStrategyInterface;

/**
 * Strategy to be used for retrieving object identities from domain objects.
 */
class ObjectIdentityRetrievalStrategy implements ObjectIdentityRetrievalStrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function getObjectIdentity($domainObject)
    {
        try {
            return ObjectIdentity::fromDomainObject($domainObject);
        } catch (InvalidDomainObjectException $e) {
            return;
        }
    }
}
