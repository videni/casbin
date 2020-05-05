<?php

namespace Videni\Bundle\CasbinBundle\Acl\Domain;

use Videni\Bundle\CasbinBundle\Model\SecurityIdentityInterface;

/**
 * A SecurityIdentity implementation used for actual users.
 */
final class UserSecurityIdentity implements SecurityIdentityInterface
{
    private $username;
    private $class;

    /**
     * Constructor.
     *
     * @param string $username the username representation
     * @param string $class    the user's fully qualified class name
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($username, $class)
    {
        if ('' === $username || null === $username) {
            throw new \InvalidArgumentException('$username must not be empty.');
        }
        if (empty($class)) {
            throw new \InvalidArgumentException('$class must not be empty.');
        }

        $this->username = (string) $username;
        $this->class = $class;
    }

    /**
     * Returns the username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Returns the user's class name.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function equals(SecurityIdentityInterface $sid)
    {
        if (!$sid instanceof self) {
            return false;
        }

        return $this->username === $sid->getUsername()
               && $this->class === $sid->getClass();
    }

    /**
     * A textual representation of this security identity.
     *
     * This is not used for equality comparison, but only for debugging.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s:%s', $this->class, $this->username);
    }
}
