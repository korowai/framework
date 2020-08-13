<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter\ExtLdap;

use Korowai\Testing\Ldaplib\TestCase;

use Korowai\Lib\Ldap\Adapter\ExtLdap\BindingTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\BindingInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class BindingTraitTest extends TestCase
{
    use BindingTestTrait;

    public function createBindingInstance(LdapLinkInterface $ldapLink, bool $bound = false) : BindingInterface
    {
        return new class ($ldapLink, $bound) implements BindingInterface {
            use BindingTrait;

            private $ldapLink;

            public function __construct(LdapLinkInterface $ldapLink, bool $bound = false)
            {
                $this->ldapLink = $ldapLink;
                $this->bound = $bound;
            }

            public function getLdapLink() : LdapLinkInterface
            {
                return $this->ldapLink;
            }
        };
    }
}

// vim: syntax=php sw=4 ts=4 et:
