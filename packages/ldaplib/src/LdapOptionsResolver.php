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

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkOptionsTrait;

/**
 * @todo Write documentation
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapOptionsResolver implements LdapOptionsResolverInterface
{
    use LdapLinkOptionsTrait;

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
        return $this->resolver->resolve($options);
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
            $this->configureNestedOptionsResolver($nestedResolver);
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
            'host' => 'localhost',
            'uri' => null,
            'encryption' => 'none',
        ]);

        $resolver->setDefault('port', function (Options $options) {
            return ('ssl' === $options['encryption']) ? 636 : 389;
        });

        $resolver->setDefault('uri', function (Options $options) {
            $port = (
                'ssl' === $options['encryption'] && $options['port'] !== 636 ||
                'ssl' !== $options['encryption'] && $options['port'] !== 389
            ) ? sprintf(':%d', $options['port']) : '';
            $protocol = ('ssl' === $options['encryption'] ? 'ldaps' : 'ldap');
            return  $protocol .  '://' . $options['host'] .  $port;
        });

        $resolver->setAllowedTypes('host', 'string');
        $resolver->setAllowedTypes('port', 'numeric');
        $resolver->setAllowedTypes('uri', 'string');
        $resolver->setAllowedValues('encryption', ['none', 'ssl', 'tls']);

        $resolver->setAllowedValues('port', function (int $port) : bool {
            return $port > 0 && $port < 65536;
        });
    }

    /**
     * Configures OptionsResolver for nested $options['options'].
     *
     * @param OptionsResolver $resolver The resolver to be configured
     */
    private function configureNestedOptionsResolver(OptionsResolver $resolver) : void
    {
        $this->configureLdapLinkOptions($resolver);
    }
}

// vim: syntax=php sw=4 ts=4 et:
