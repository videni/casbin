<?php

namespace Videni\Bundle\CasbinBundle\Acl\Domain;

use Videni\Bundle\CasbinBundle\Model\SecurityIdentityInterface;

/**
 * A SecurityIdentity implementation for roles.
 */
final class RoleSecurityIdentity implements SecurityIdentityInterface
{
    private $role;

    /**
     * Constructor.
     *
     * @param mixed $role a Role instance, or its string representation
     */
    public function __construct($role)
    {
        $this->role = $role;
    }

    /**
     * Returns the role name.
     *
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * {@inheritdoc}
     */
    public function equals(SecurityIdentityInterface $sid)
    {
        if (!$sid instanceof self) {
            return false;
        }

        return $this->role === $sid->getRole();
    }

    /**
     * Returns a textual representation of this security identity.
     *
     * This is solely used for debugging purposes, not to make an equality decision.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->role;
    }
}
