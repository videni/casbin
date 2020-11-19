<?php

namespace Videni\Casbin\Model;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Interface for retrieving security identities from tokens.
 */
interface SecurityIdentityRetrievalStrategyInterface
{
    /**
     * Retrieves the available security identities for the given token.
     *
     * The order in which the security identities are returned is significant.
     * Typically, security identities should be ordered from most specific to
     * least specific.
     *
     * @param TokenInterface $token
     *
     * @return SecurityIdentityInterface[] An array of SecurityIdentityInterface implementations
     */
    public function getSecurityIdentities(TokenInterface $token);
}
