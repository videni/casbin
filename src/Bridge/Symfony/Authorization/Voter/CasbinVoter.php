<?php

namespace Videni\Casbin\Authorization\Voter;

use Videni\Casbin\Model\ObjectIdentityInterface;
use Videni\Casbin\Model\ObjectIdentityRetrievalStrategyInterface;
use Videni\Casbin\Model\SecurityIdentityRetrievalStrategyInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Videni\Casbin\EnforcerManager;
use Psr\Log\LoggerInterface;

class CasbinVoter implements VoterInterface
{
    private $manager;
    private $objectIdentityRetrievalStrategy;
    private $securityIdentityRetrievalStrategy;
    private $logger;
    private $allowIfObjectIdentityUnavailable;

    public function __construct(
        EnforcerManager $manager,
        ObjectIdentityRetrievalStrategyInterface $oidRetrievalStrategy,
        SecurityIdentityRetrievalStrategyInterface $sidRetrievalStrategy,
        LoggerInterface $logger = null,
        $allowIfObjectIdentityUnavailable = true
    ) {
        $this->manager = $manager;
        $this->objectIdentityRetrievalStrategy = $oidRetrievalStrategy;
        $this->securityIdentityRetrievalStrategy = $sidRetrievalStrategy;
        $this->logger = $logger;
        $this->allowIfObjectIdentityUnavailable = $allowIfObjectIdentityUnavailable;
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        foreach ($attributes as $attribute) {
            if (null === $object) {
                if (null !== $this->logger) {
                    $this->logger->debug(sprintf(
                        'Object identity unavailable. Voting to %s.',
                        $this->allowIfObjectIdentityUnavailable ? 'grant access' : 'abstain'
                    ));
                }

                return $this->allowIfObjectIdentityUnavailable ? self::ACCESS_GRANTED : self::ACCESS_ABSTAIN;
            }

            if ($object instanceof ObjectIdentityInterface) {
                $oid = $object;
            } elseif (null === $oid = $this->objectIdentityRetrievalStrategy->getObjectIdentity($object)) {
                if (null !== $this->logger) {
                    $this->logger->debug(sprintf(
                        'Object identity unavailable. Voting to %s.',
                        $this->allowIfObjectIdentityUnavailable ? 'grant access' : 'abstain'
                    ));
                }

                return $this->allowIfObjectIdentityUnavailable ? self::ACCESS_GRANTED : self::ACCESS_ABSTAIN;
            }

            $sids = $this->securityIdentityRetrievalStrategy->getSecurityIdentities($token);

            $enforcer = $this->manager->getEnforcer();
            foreach($sids as $sid) {
                if($enforcer->enforce($sid, $oid, $attribute)) {
                    if (null !== $this->logger) {
                        $this->logger->debug('ACL found, permission granted. Voting to grant access.');
                    }

                    return self::ACCESS_GRANTED;
                }
            }

            return self::ACCESS_DENIED;
        }
    }
}
