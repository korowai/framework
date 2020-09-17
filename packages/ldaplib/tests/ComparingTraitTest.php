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
use Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait;

use Korowai\Lib\Ldap\ComparingTrait;
use Korowai\Lib\Ldap\ComparingInterface;
use Korowai\Lib\Ldap\CompareQueryInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\ComparingTrait
 * @covers \Korowai\Tests\Lib\Ldap\ComparingTestTrait
 * @covers \Korowai\Testing\LdapLib\ExamineLdapLinkErrorHandlerTrait
 */
final class ComparingTraitTest extends TestCase
{
    use ComparingTestTrait;
    use ExamineLdapLinkErrorHandlerTrait;

    // required by ComparingTestTrait
    public function createComparingInstance(LdapLinkInterface $ldapLink) : ComparingInterface
    {
        return new class ($ldapLink) implements ComparingInterface {
            use ComparingTrait;

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

// vim: syntax=php sw=4 ts=4 et tw=119:
