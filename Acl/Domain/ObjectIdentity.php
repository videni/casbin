<?php


namespace Videni\Bundle\CasbinBundle\Acl\Domain;

use Videni\Bundle\CasbinBundle\Exception\InvalidDomainObjectException;
use Videni\Bundle\CasbinBundle\Util\ClassUtils;
use Videni\Bundle\CasbinBundle\Model\ObjectIdentityInterface;

/**
 * ObjectIdentity implementation.
 */
final class ObjectIdentity implements ObjectIdentityInterface
{
    private $identifier;
    private $type;

    /**
     * Constructor.
     *
     * @param string $identifier
     * @param string $type
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($identifier, $type)
    {
        if ('' === $identifier) {
            throw new \InvalidArgumentException('$identifier cannot be empty.');
        }
        if (empty($type)) {
            throw new \InvalidArgumentException('$type cannot be empty.');
        }

        $this->identifier = $identifier;
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function equals(ObjectIdentityInterface $identity)
    {
        // comparing the identifier with === might lead to problems, so we
        // waive this restriction
        return $this->identifier == $identity->getIdentifier()
               && $this->type === $identity->getType();
    }

    /**
     * Returns a textual representation of this object identity.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s:%s', $this->type, $this->identifier);
    }
}
