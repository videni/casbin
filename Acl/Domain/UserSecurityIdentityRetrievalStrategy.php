<?php

namespace Videni\Bundle\CasbinBundle\Acl\Domain;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserSecurityIdentityRetrievalStrategy
{
    private $resourceIdentityRetrievalStrategy;

    public function __construct(ResourceIdentityRetrievalStrategy $resourceIdentityRetrievalStrategy)
    {
        $this->resourceIdentityRetrievalStrategy = $resourceIdentityRetrievalStrategy;
    }

    /**
     * Creates a user security identity from a UserInterface.
     *
     * @param UserInterface $user
     *
     * @return UserSecurityIdentity
     */
    public function fromAccount(UserInterface $user)
    {
        return new UserSecurityIdentity(
            $user->getUsername(),
            $this->resourceIdentityRetrievalStrategy->fromObject($user)
        );
    }

    /**
     * Creates a user security identity from a TokenInterface.
     *
     * @param TokenInterface $token
     *
     * @return UserSecurityIdentity
     */
    public function fromToken(TokenInterface $token)
    {
        $user = $token->getUser();

        if ($user instanceof UserInterface) {
            return $this->fromAccount($user);
        }

        return new UserSecurityIdentity((string) $user, $user);
    }
}
