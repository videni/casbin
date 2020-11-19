<?php

namespace Videni\Casbin\Model;

/**
 * This method can be implemented by domain objects which you want to store
 * ACLs for if they do not have a getId() method, or getId() does not return
 * a unique identifier.
 */
interface DomainObjectInterface
{
    /**
     * Returns a unique identifier for this domain object.
     *
     * @return string
     */
    public function getObjectIdentifier();
}
