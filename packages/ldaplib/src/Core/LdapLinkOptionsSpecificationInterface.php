<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Core;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Configures symfony's OptionsResolver for nested LdapLink options.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapLinkOptionsSpecificationInterface
{
    /**
     * Returns the encapsulated LdapLinkOptionsMapperInterface.
     *
     * @psalm-mutation-free
     */
    public function getOptionsMapper(): LdapLinkOptionsMapperInterface;

    /**
     * Configures symfony's  OptionsResolver to parse LdapLink options.
     */
    public function configureOptionsResolver(OptionsResolver $resolver): void;
}

// vim: syntax=php sw=4 ts=4 et tw=119:
