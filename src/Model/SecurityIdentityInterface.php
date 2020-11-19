<?php

namespace Videni\Casbin\Model;

/**
 * This interface provides an additional level of indirection, so that
 * we can work with abstracted versions of security objects and do
 * not have to save the entire objects.
 */
interface SecurityIdentityInterface
{
    /**
     * This method is used to compare two security identities in order to
     * not rely on referential equality.
     *
     * @param SecurityIdentityInterface $identity
     */
    public function equals(SecurityIdentityInterface $identity);
}
