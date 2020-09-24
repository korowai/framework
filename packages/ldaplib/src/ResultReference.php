<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap;

use function Korowai\Lib\Context\with;
use Korowai\Lib\Ldap\Core\LdapLinkErrorHandler;
use Korowai\Lib\Ldap\Core\LdapResultReferenceInterface;
use Korowai\Lib\Ldap\Core\LdapResultReferenceWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapResultReferenceWrapperTrait;

/**
 * Wrapper for ldap reference result resource.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ResultReference implements ResultReferenceInterface, LdapResultReferenceWrapperInterface
{
    use LdapResultReferenceWrapperTrait;

    /** @var null|ResultReferralIteratorInterface */
    private $iterator;

    /**
     * Initializes the ``ResultReference`` instance.
     */
    public function __construct(LdapResultReferenceInterface $ldapResultReference)
    {
        $this->ldapResultReference = $ldapResultReference;
    }

    /**
     * Returns referrals.
     */
    public function getReferrals(): array
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
     */
    public function getReferralIterator(): ResultReferralIteratorInterface
    {
        if (!isset($this->iterator)) {
            $this->iterator = new ResultReferralIterator($this->getReferrals());
        }

        return $this->iterator;
    }

    /**
     * Returns iterator over reference's referrals.
     */
    public function getIterator(): ResultReferralIteratorInterface
    {
        return $this->getReferralIterator();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
