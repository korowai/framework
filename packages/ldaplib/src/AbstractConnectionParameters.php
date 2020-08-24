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

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

//use Korowai\Lib\Ldap\Adapter\AdapterInterface;
//use Korowai\Lib\Ldap\Adapter\AdapterFactoryInterface;
//use Korowai\Lib\Ldap\Adapter\BindingInterface;
//use Korowai\Lib\Ldap\Adapter\EntryManagerInterface;
//use Korowai\Lib\Ldap\Adapter\SearchQueryInterface;
//use Korowai\Lib\Ldap\Adapter\CompareQueryInterface;
//use Korowai\Lib\Ldap\Adapter\ResultInterface;
//
//use InvalidArgumentException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractConnectionParameters implements ConnectionParametersInterface
{
    /**
     * @var array
     */
    private $config;

    /**
     * Initializes the connection.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $this->resolveConfig($config);
    }

    /**
     * Returns the config provided to the constructor.
     *
     * @return array
     */
    public function getConfig() : array
    {
        return $this->config;
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    public function host() : string
    {
        return $this->config['host'];
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    public function port() : int
    {
        return $this->config['port'];
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    public function encryption() : string
    {
        return $this->config['encryption'];
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    public function uri() : string
    {
        return $this->config['uri'];
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    public function options() : array
    {
        return $this->config['options'];
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
