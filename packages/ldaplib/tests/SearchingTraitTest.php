<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap;

use Korowai\Testing\Ldaplib\TestCase;
use Korowai\Testing\Ldaplib\ExamineCallWithLdapTriggerErrorTrait;

use Korowai\Lib\Ldap\SearchingTrait;
use Korowai\Lib\Ldap\SearchingInterface;
use Korowai\Lib\Ldap\SearchQueryInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\SearchingTrait
 */
final class SearchingTraitTest extends TestCase
{
    use SearchingTestTrait;
    use ExamineCallWithLdapTriggerErrorTrait;

    public function createSearchingInstance(LdapLinkInterface $ldapLink) : SearchingInterface
    {
        return new class ($ldapLink) implements SearchingInterface {
            use SearchingTrait;

            private $ldapLink;

            public function __construct(LdapLinkInterface $ldapLink)
            {
                $this->ldapLink = $ldapLink;
            }

            public function getLdapLink() : LdapLinkInterface
            {
                return $this->ldapLink;
            }
        };
    }
}

// vim: syntax=php sw=4 ts=4 et:
