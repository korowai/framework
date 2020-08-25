<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter\ExtLdap;

use Korowai\Lib\Ldap\ResultReferenceInterface;
use Korowai\Lib\Ldap\ResultReferralIteratorInterface;
use function Korowai\Lib\Context\with;

/**
 * Wrapper for ldap reference result resource.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ResultReference implements ResultReferenceInterface, LdapResultReferenceWrapperInterface
{
    use LdapResultReferenceWrapperTrait;

    /** @var ResultReferralIteratorInterface|null */
    private $iterator;

    /**
     * Initializes the ``ResultReference`` instance
     *
     * @param  LdapResultReferenceInterface $ldapResultReference
     */
    public function __construct(LdapResultReferenceInterface $ldapResultReference)
    {
        $this->ldapResultReference = $ldapResultReference;
    }

    /**
     * Returns referrals
     * @return array
     */
    public function getReferrals() : array
    {
        $reference = $this->getLdapResultReference();

        with(LdapLinkErrorHandler::fromLdapResultWrapper($reference))(
            function () use ($reference, &$referrals) {
                return $reference->parse_reference($referrals);
            }
        );

        if (!isset($referrals)) {
            $referrals = [];
        }

        return $referrals;
    }

    /**
     * It always returns same instance. When used for the first
     * time, the iterator is set to point to the first attribute of the entry.
     * For subsequent calls, the method just return the iterator without
     * altering its position.
     *
     * @return ResultReferralIteratorInterface
     */
    public function getReferralIterator() : ResultReferralIteratorInterface
    {
        if (!isset($this->iterator)) {
            $this->iterator = new ResultReferralIterator($this->getReferrals());
        }
        return $this->iterator;
    }

    /**
     * Returns iterator over reference's referrals
     *
     * @return ResultReferralIteratorInterface
     */
    public function getIterator() : ResultReferralIteratorInterface
    {
        return $this->getReferralIterator();
    }
}

// vim: syntax=php sw=4 ts=4 et:
