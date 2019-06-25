<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Service\Ldap;

use Korowai\Component\Ldap\Ldap;
use Korowai\Service\Ldap\LdapInstance;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

class LdapService
{
    /**
     * Ldap instances
     *
     * @var array
     */
    protected $instances = array();

    /**
     * Config used to generate instances
     *
     * @var array
     */
    protected $config;

    /**
     * Initializes the LdapService
     */
    public function __construct(array $config = null)
    {
        if(isset($config)) {
            $this->configure($config);
        }
    }

    /**
     * Set configuration for later use by createLdapInstance().
     *
     * @param array $config Configuration options used to configure every new
     *                      adapter instance created by createAdapter().
     */
    public function configure(array $config)
    {
        $resolver = new OptionsResolver;
        $dbResolver = new OptionsResolver;
        $this->configureCommonOptionsResolver($resolver);
        $this->configureDatabaseOptionsResolver($dbResolver);

        $this->setConfig($config, $resolver, $dbResolver);
    }

    /**
     * Configure OptionsResolver for the service config.
     */
    protected function configureCommonOptionsResolver(OptionsResolver $resolver)
    {
        $resolver->setRequired('databases');
        $resolver->setAllowedTypes('databases', 'array[]');
        $resolver->setNormalizer('databases', function (Options $options, $value) {
            return [];
        });
    }

    /**
     * Configure OptionResolver for a single entry in $config['database'].
     * The $resolver shall be applied to every item in the $config array.
     */
    protected function configureDatabaseOptionsResolver(OptionsResolver $resolver)
    {
        $resolver->setRequired('id');
        $resolver->setRequired('ldap');
        $resolver->setDefined('factory');
        $resolver->setRequired('name');
        $resolver->setDefined('description');
        $resolver->setDefined('base');

        $resolver->setDefault('bind', function (OptionsResolver $bindResolver) {
            $bindResolver->setDefined(['dn', 'password']);
            $bindResolver->setAllowedTypes('dn', 'string');
            $bindResolver->setAllowedTypes('password', 'string');

            if(function_exists('ldap_explode_dn')) {
                $bindResolver->setAllowedValues('dn', function ($value) {
                    return ldap_explode_dn($value, 0) !== false;
                });
            }
        });


        $resolver->setAllowedTypes('id', 'int');
        $resolver->setAllowedTypes('ldap', 'array');
        $resolver->setAllowedTypes('factory', 'string');
        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('description',  'string');
        $resolver->setAllowedTypes('base', 'string');


        $resolver->setAllowedValues('factory', function ($value) {
            return is_subclass_of($value, \Korowai\Component\Ldap\Adapter\AdapterFactoryInterface::class);
        });
    }

    /**
     * Resolve config options.
     *
     * @param array $config
     * @param OptionsResolver $resolver
     * @param OptionsResolver $dbResolver
     */
    protected function resolveConfig(array $config, OptionsResolver $resolver, OptionsResolver $dbResolver)
    {
        $resolved = $resolver->resolve($config);
        $dbs = array_map(array($dbResolver, 'resolve'), array_values($config['databases']));
        $ids = array_map(function ($db) { return $db['id']; }, $dbs);
        if(count($ids) !== count(array_unique($ids))) {
            // throw exceptino some ids got duplicated
        }
        $resolved['databases'] = array_combine($ids, $dbs);
        return $resolved;
    }

    /**
     * Setup new config array.
     *
     * @param array $config
     * @param OptionsResolver $resolver
     * @param OptionsResolver $dbResolver
     */
    protected function setConfig(array $config, OptionsResolver $resolver, OptionsResolver $dbResolver)
    {
        $this->config = $this->resolveConfig($config, $resolver, $dbResolver);
    }

    /**
     * Return the whole config.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Return config for database identified by $id.
     *
     * @param int $id
     * @return array|null
     */
    public function getDatabaseConfig(int $id)
    {
        return $this->config['databases'][$id] ?? null;
    }

    /**
     * Get an array of valid IDs.
     *
     * @return array
     */
    public function getIds() : array
    {
        return array_keys($this->config);
    }

    /**
     * Get Ldap instance with given ID.
     *
     * @param string $id
     * @return \Korowai\Component\Ldap\LdapInterface|null
     */
    public function getLdapInstance(string $id)
    {
        if(!isset($this->instances[$id])) {
            if(null === ($config = $this->getDatabaseConfig($id))) {
                return null;
            }
            $this->instances[$id] = $this->createLdapInstance($config);
        }
        return $this->instances[$id];
    }

    /**
     * Create Ldap instance according to $dbConfig
     *
     * @param array $dbConfig
     * @return LdapInstance
     */
    public function createLdapInstance(array $dbConfig) : LdapInstance
    {
        $factory = $dbConfig['factory'] ?? null;
        $ldap = Ldap::createWithConfig($dbConfig['ldap'], $factory);
        return new LdapInstance($ldap, $dbConfig);
    }
}

// vim: syntax=php sw=4 ts=4 et:
