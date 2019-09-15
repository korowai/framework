<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\LdapService
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Service\Ldap;

use Korowai\Component\Ldap\Ldap;
use Korowai\Component\Ldap\LdapInterface;
use Korowai\Service\Ldap\Exception\LdapServiceException;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class LdapService extends AbstractLdapService
{
    /**
     * Ldap instances
     *
     * @var array
     */
    protected $ldaps = array();

    /**
     * Configuration (resolved, except for the $config['databases'][]['ldap'])
     *
     * @var array
     */
    protected $config = array();

    /**
     * Initializes the LdapService
     */
    public function __construct(array $config = null)
    {
        if (isset($config)) {
            $this->configure($config);
        }
    }

    /**
     * Set configuration for later use by createLdap().
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
        $resolver->setNormalizer('databases', function (Options $options, $dbs) {

            $ids = array_map(function ($db) {
                return $db['id'];
            }, $dbs);
            if (count($ids) !== count(array_unique($ids))) {
                throw new InvalidOptionsException('"id" must be unique across all "databases"');
            }

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

        $resolver->setDefault('meta', function (OptionsResolver $metaResolver) {
            $this->configureMetaOptionsResolver($metaResolver);
        });

        $resolver->setAllowedValues('factory', function ($value) {
            return is_subclass_of($value, \Korowai\Component\Ldap\Adapter\AdapterFactoryInterface::class);
        });
    }

    protected function configureMetaOptionsResolver(OptionsResolver $resolver)
    {
          $resolver->setRequired('name');
          $resolver->setDefined('description');
          $resolver->setDefined('base');

//          $resolver->setDefault('bind', function (OptionsResolver $bindResolver) {
//              $bindResolver->setDefined(['dn', 'password']);
//              $bindResolver->setAllowedTypes('dn', 'string');
//              $bindResolver->setAllowedTypes('password', 'string');
//
//              if (function_exists('ldap_explode_dn')) {
//                  $bindResolver->setAllowedValues('dn', function ($value) {
//                      return ldap_explode_dn($value, 0) !== false;
//                  });
//              }
//          });

          $resolver->setAllowedTypes('name', 'string');
          $resolver->setAllowedTypes('description', 'string');
          $resolver->setAllowedTypes('base', 'string');
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
        $ids = array_map(function ($db) {
            return $db['id'];
        }, $dbs);
        if (count($ids) !== count(array_unique($ids))) {
            throw new InvalidOptionsException('"id" must be unique across all "databases"');
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
    public function getConfig() : array
    {
        return $this->config;
    }

    /**
     * Return config for database identified by $id.
     *
     * @param int $id
     * @return array|null
     */
    public function getDatabaseConfig(int $id) : array
    {
        if (!array_key_exists($id, $this->config['databases'])) {
            throw new LdapServiceException("undefined LDAP database (id=${id})");
        }
        return $this->config['databases'][$id];
    }

    /**
     * Get database meta information.
     *
     * @return array
     * @throws \Korowai\Service\Ldap\Exception\LdapServiceException
     */
    public function getDatabaseMeta(int $id) : array
    {
        $db = $this->getDatabaseConfig($id);
        return $db['meta'];
    }

    /**
     * Get an array of valid IDs.
     *
     * @return array
     */
    public function getDatabaseIds() : array
    {
        return array_keys($this->config['databases']);
    }

    /**
     * Get Ldap instance with given ID.
     *
     * @param int $id
     * @return \Korowai\Component\Ldap\LdapInterface|null
     * @throws \Korowai\Service\Ldap\Exception\LdapServiceException
     */
    public function getLdap(int $id) : LdapInterface
    {
        if (!isset($this->ldaps[$id])) {
            $this->ldaps[$id] = $this->createLdapForId($id);
        }
        return $this->ldaps[$id];
    }

    /**
     * Delete Ldap instance with given ID.
     *
     * @param int $id
     * @return \Korowai\Component\Ldap\LdapInterface|null
     */
    public function unsetLdap(int $id)
    {
        if (array_key_exists($id, $this->ldaps)) {
            unset($this->ldaps[$id]);
        }
    }

    /**
     * Create Ldap instance according to $dbConfig
     *
     * @param int $id
     * @return LdapInstance
     * @throws \Korowai\Service\Ldap\Exception\LdapServiceException
     */
    protected function createLdapForId(int $id) : LdapInterface
    {
        $db = $this->getDatabaseConfig($id);
        return Ldap::createWithConfig($db['ldap'], $db['factory'] ?? null);
    }
}

// vim: syntax=php sw=4 ts=4 et:
