<?php

namespace Videni\Bundle\CasbinBundle\Acl\Domain;

use Videni\Bundle\CasbinBundle\Exception\InvalidDomainObjectException;
use Videni\Bundle\CasbinBundle\Model\ObjectIdentityRetrievalStrategyInterface;
use Videni\Bundle\CasbinBundle\Model\DomainObjectInterface;

/**
 * Strategy to be used for retrieving object identities from domain objects.
 */
class ObjectIdentityRetrievalStrategy implements ObjectIdentityRetrievalStrategyInterface
{
    private $resourceIdentityRetrievalStrategy;

    public function __construct(ResourceIdentityRetrievalStrategy $resourceIdentityRetrievalStrategy)
    {
        $this->resourceIdentityRetrievalStrategy = $resourceIdentityRetrievalStrategy;
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectIdentity($domainObject)
    {
        try {
            return $this->fromDomainObject($domainObject);
        } catch (InvalidDomainObjectException $e) {
            return;
        }
    }

     /**
     * Constructs an ObjectIdentity for the given domain object.
     *
     * @param object $domainObject
     *
     * @throws InvalidDomainObjectException
     *
     * @return ObjectIdentity
     */
    public function fromDomainObject($domainObject)
    {
        if (!is_object($domainObject)) {
            throw new InvalidDomainObjectException('$domainObject must be an object.');
        }

        try {
            $resourceIdentity = $this->resourceIdentityRetrievalStrategy->fromObject($domainObject);
            if ($domainObject instanceof DomainObjectInterface) {
                return new ObjectIdentity($domainObject->getObjectIdentifier(), $resourceIdentity);
            } elseif (method_exists($domainObject, 'getId')) {
                return new ObjectIdentity((string) $domainObject->getId(), $resourceIdentity);
            }
        } catch (\InvalidArgumentException $e) {
            throw new InvalidDomainObjectException($e->getMessage(), 0, $e);
        }

        throw new InvalidDomainObjectException('$domainObject must either implement the DomainObjectInterface, or have a method named "getId".');
    }
}
