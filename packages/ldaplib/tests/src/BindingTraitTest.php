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

use Korowai\Lib\Ldap\BindingInterface;
use Korowai\Lib\Ldap\BindingTrait;
use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait;
use Korowai\Testing\Ldaplib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\BindingTrait
 * @covers \Korowai\Tests\Lib\Ldap\BindingTestTrait
 *
 * @internal
 */
final class BindingTraitTest extends TestCase
{
    use BindingTestTrait;
    use ExamineLdapLinkErrorHandlerTrait;

    // required by BindingTestTrait
    public function createBindingInstance(LdapLinkInterface $ldapLink, bool $bound = false): BindingInterface
    {
        return new class($ldapLink, $bound) implements BindingInterface {
            use BindingTrait;

            private $ldapLink;

            public function __construct(LdapLinkInterface $ldapLink, bool $bound = false)
            {
                $this->ldapLink = $ldapLink;
                $this->bound = $bound;
            }

            public function getLdapLink(): LdapLinkInterface
            {
                return $this->ldapLink;
            }
        };
    }
}

// vim: syntax=php sw=4 ts=4 et:
