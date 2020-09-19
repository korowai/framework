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

use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapLinkFactory;
use Korowai\Lib\Ldap\Core\LdapLinkFactoryInterface;
use Korowai\Lib\Ldap\Core\LdapLinkConstructor;
use Korowai\Lib\Ldap\Core\LdapLinkConstructorInterface;
use Korowai\Lib\Ldap\Core\LdapLinkConfigResolver;
use Korowai\Lib\Ldap\Core\LdapLinkConfigResolverInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperTrait;
use Korowai\Lib\Ldap\BindingTrait;
use Korowai\Lib\Ldap\EntryManagerTrait;

use InvalidArgumentException;

/**
 * @todo Write documentation
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class Ldap implements LdapInterface, LdapLinkWrapperInterface
{
    use LdapLinkWrapperTrait;
    use BindingTrait;
    use ComparingTrait;
    use SearchingTrait;
    use EntryManagerTrait;

    /**
     * Create new Ldap instance
     *
     * @param LdapLinkInterface $ldapLink
     * @param bool $bound
     */
    public function __construct(LdapLinkInterface $ldapLink, bool $bound = false)
    {
        $this->ldapLink = $ldapLink;
        $this->bound = $bound;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
