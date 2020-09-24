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

use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\SearchingInterface;
use Korowai\Lib\Ldap\SearchingTrait;
use Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait;
use Korowai\Testing\Ldaplib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\SearchingTrait
 * @covers \Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait
 * @covers \Korowai\Tests\Lib\Ldap\SearchingTestTrait
 *
 * @internal
 */
final class SearchingTraitTest extends TestCase
{
    use SearchingTestTrait;
    use ExamineLdapLinkErrorHandlerTrait;

    // required by SearchingTestTrait
    public function createSearchingInstance(LdapLinkInterface $ldapLink): SearchingInterface
    {
        return new class($ldapLink) implements SearchingInterface {
            use SearchingTrait;

            private $ldapLink;

            public function __construct(LdapLinkInterface $ldapLink)
            {
                $this->ldapLink = $ldapLink;
            }

            public function getLdapLink(): LdapLinkInterface
            {
                return $this->ldapLink;
            }
        };
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
