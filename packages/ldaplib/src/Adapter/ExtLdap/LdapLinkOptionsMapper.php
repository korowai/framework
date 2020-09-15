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

/**
 * Resolves LDAP options for LdapLinkInterface::set_option().
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapLinkOptionsMapper implements LdapLinkOptionsMapperInterface
{
    /**
     * {@inheritdoc}
     */
    public function map(array $options) : array
    {
        return static::mapOptionsNamesToIds($options);
    }

    private static function mapOptionsNamesToIds(array $options) : array
    {
        $ids = array_map([LdapLinkOptionsDeclaration::class, 'getOptionId'], array_keys($options));
        return array_combine($ids, $options);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
