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

use Korowai\Lib\Rfc\Rfc3986;
use function Korowai\Lib\Compat\preg_match;

/**
 * @todo Write documentation
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapLinkConfigResolver implements LdapLinkConfigResolverInterface
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
        $resolved = $this->resolver->resolve($options);
        if (($options = $resolved['options'] ?? null) !== null) {
            $resolved['options'] = static::mapOptionsNamesToIds($options);
        }
        return $resolved;
    }

    private static function mapOptionsNamesToIds(array $options) : array
    {
        return array_combine(array_map([LdapLinkOptions::class, 'getOptionId'], array_keys($options)), $options);
    }

    /**
     * Configures OptionsResolver.
     *
     * @param OptionsResolver $resolver The resolver to be configured
     */
    private function configureOptionsResolver(OptionsResolver $resolver) : void
    {
        $this->configureTopLevelOptionsResolver($resolver);
        $resolver->setDefault('options', function (OptionsResolver $nestedResolver) : void {
            LdapLinkOptions::configureOptionsResolver($nestedResolver);
        });
    }

    /**
     * Configures OptionsResolver for top-level options.
     *
     * @param OptionsResolver $resolver The resolver to be configured
     */
    private function configureTopLevelOptionsResolver(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'uri' => 'ldap://localhost',
            'tls' => false,
        ]);
        $resolver->setAllowedTypes('uri', 'string');
        $resolver->setAllowedTypes('tls', 'bool');

        $resolver->setAllowedValues('uri', function (string $uri) : bool {
            if (!preg_match('/^'.Rfc3986::URI.'$/', $uri, $matches, PREG_UNMATCHED_AS_NULL)) {
                return false;
            }
            if (($matches['host'] ?? null) === null) {
                return false;
            }
            if (($port = $matches['port'] ?? null) !== null) {
                $port = intval($port);
                if ($port <= 0 || $port > 65535) {
                    return false;
                }
            }
            return true;
        });
    }
}

// vim: syntax=php sw=4 ts=4 et:
