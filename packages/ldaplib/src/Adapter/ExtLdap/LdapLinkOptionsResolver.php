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

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Resolves LDAP options for LdapLinkInterface::set_option().
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapLinkOptionsResolver implements LdapLinkOptionsResolverInterface
{
    /**
     * @var OptionsResolver
     */
    private $resolver;

    /**
     * Initializes the object.
     *
     * @param OptionsResolver|null $resolver
     */
    public function __construct(OptionsResolver $resolver = null)
    {
        if ($resolver === null) {
            $resolver = new OptionsResolver;
        }
        $this->configureOptionsResolver($resolver);
        $this->resolver = $resolver;
    }

    /**
     * Returns the encapsulated OptionsResolver instance.
     *
     * @return OptionsResolver
     */
    public function getOptionsResolver() : OptionsResolver
    {
        return $this->resolver;
    }

    /**
     * Resolves $options.
     *
     * @param array $options
     * @return array
     */
    public function resolve(array $options) : array
    {
        return static::mapOptionsNamesToIds($this->resolver->resolve($options));
    }

    /**
     * Replaces string keys with integer-valued option ids.
     *
     * @param  array $options
     * @return array
     */
    private static function mapOptionsNamesToIds(array $options) : array
    {
        $ids = array_map([LdapLinkOptionsDeclarations::class, 'getOptionId'], array_keys($options));
        return array_combine($ids, $options);
    }

    /**
     * Configures OptionsResolver.
     *
     * @param OptionsResolver $resolver The resolver to be configured
     */
    private function configureOptionsResolver(OptionsResolver $resolver) : void
    {
        LdapLinkOptionsDeclarations::configureOptionsResolver($resolver);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
