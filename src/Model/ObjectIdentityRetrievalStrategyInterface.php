<?php

namespace Videni\Casbin\Model;

/**
 * Retrieves the object identity for a given domain object.
 */
interface ObjectIdentityRetrievalStrategyInterface
{
    /**
     * Retrieves the object identity from a domain object.
     *
     * @param object $domainObject
     *
     * @return ObjectIdentityInterface
     */
    public function getObjectIdentity($domainObject);
}
