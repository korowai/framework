<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * Abstract base class for Adapter factories.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractAdapterFactory implements AdapterFactoryInterface
{
    /**
     * @var array
     */
    private $config;

    /**
     * Creates an AbstractAdapterFactory
     *
     * @param  array $config A config to be passed to configure().
     */
    public function __construct(array $config = [])
    {
        $this->config = $this->resolveConfig($config);
    }

    /**
     * {@inheritdoc}
     */
    public function configure(array $config) : void
    {
        $this->config = $this->resolveConfig($config);
    }

    /**
     * Return configuration array previously set with configure().
     *
     * If configuration is not set yet, null is returned.
     *
     * @return array
     *
     * @psalm-mutation-free
     */
    public function getConfig() : array
    {
        return $this->config;
    }

    /**
     * Resolves the $config array.
     *
     * @param array $config
     * @return array Resolved config
     */
    protected function resolveConfig(array $config) : array
    {
        $resolver = new OptionsResolver;

        $this->configureOptionsResolver($resolver);
        $resolver->setDefault('options', function (OptionsResolver $nestedResolver) : void {
            $this->configureNestedOptionsResolver($nestedResolver);
        });

        return $resolver->resolve($config);
    }

    /**
     * Configures OptionsResolver for this AdapterFactory
     *
     * This method only configures common config options, that are provider
     * independent. Provider-specific options should be implented as nested
     * options. They should be configured by `configureNestedOptionsResolver()`.
     *
     * @param OptionsResolver $resolver The resolver to be configured
     */
    protected function configureOptionsResolver(OptionsResolver $resolver) : void
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
     * Configures options resolver for nested options (provider-specific)
     *
     * The resolver passed to method as $resolver will be responsible for
     * resolving ``$config['options']`` array, i.e. options nested in
     * config array. The nested options are thought to be adapter-specific
     * (e.g specific to ext-ldap).
     *
     * @param OptionsResolver $resolver The resolver to be configured
     */
    abstract protected function configureNestedOptionsResolver(OptionsResolver $resolver) : void;
}

// vim: syntax=php sw=4 ts=4 et:
