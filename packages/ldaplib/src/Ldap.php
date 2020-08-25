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

use Korowai\Lib\Ldap\AdapterInterface;
use Korowai\Lib\Ldap\AdapterFactoryInterface;
use Korowai\Lib\Ldap\BindingInterface;
use Korowai\Lib\Ldap\EntryManagerInterface;
use Korowai\Lib\Ldap\SearchQueryInterface;
use Korowai\Lib\Ldap\CompareQueryInterface;
use Korowai\Lib\Ldap\ResultInterface;

use InvalidArgumentException;

/**
 * A facade to ldap component. Creates connection, binds, reads from and writes
 * to ldap.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class Ldap extends AbstractLdap
{
    protected static $defaultAdapterFactory = '\Korowai\Lib\Ldap\Adapter\ExtLdap\AdapterFactory';

    /** @var AdapterInterface */
    private $adapter;

    /**
     *
     * @param  array $config
     * @param  string $factoryClass
     *
     * @return Ldap
     * @throws InvalidArgumentException
     */
    public static function createWithConfig(array $config = [], string $factoryClass = null)
    {
        if (!isset($factoryClass)) {
            $factoryClass = static::$defaultAdapterFactory;
        } else {
            static::checkFactoryClassArg($factoryClass, __METHOD__, 2);
        }
        $factory = new $factoryClass();
        $factory->configure($config);
        return static::createWithAdapterFactory($factory);
    }

    /**
     * Returns new Ldap instance with adapter created by *$factory*.
     *
     * @param  AdapterFactoryInterface $factory
     * @return Ldap
     */
    public static function createWithAdapterFactory(AdapterFactoryInterface $factory)
    {
        $adapter = $factory->createAdapter();
        return new static($adapter);
    }

    /**
     * Create new Ldap instance
     *
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdapter() : AdapterInterface
    {
        return $this->adapter;
    }

    /**
     * {@inheritdoc}
     */
    public function getBinding() : BindingInterface
    {
        return $this->adapter->getBinding();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntryManager() : EntryManagerInterface
    {
        return $this->adapter->getEntryManager();
    }

    /**
     * {@inheritdoc}
     */
    public function bind(string $dn = null, string $password = null) : bool
    {
        $args = @func_get_args();
        return $this->getBinding()->bind(...$args);
    }

    /**
     * {@inheritdoc}
     */
    public function unbind() : bool
    {
        return $this->getBinding()->unbind();
    }

    /**
     * {@inheritdoc}
     */
    public function isBound() : bool
    {
        return $this->getBinding()->isBound();
    }

    /**
     * {@inheritdoc}
     */
    public function add(EntryInterface $entry) : void
    {
        $this->getEntryManager()->add($entry);
    }

    /**
     * {@inheritdoc}
     */
    public function update(EntryInterface $entry) : void
    {
        $this->getEntryManager()->update($entry);
    }

    /**
     * {@inheritdoc}
     */
    public function rename(EntryInterface $entry, string $newRdn, bool $deleteOldRdn = true) : void
    {
        $this->getEntryManager()->rename($entry, $newRdn, $deleteOldRdn);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(EntryInterface $entry) : void
    {
        $this->getEntryManager()->delete($entry);
    }

    /**
     * {@inheritdoc}
     */
    public function createSearchQuery(string $base_dn, string $filter, array $options = []) : SearchQueryInterface
    {
        return $this->getAdapter()->createSearchQuery($base_dn, $filter, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function createCompareQuery(string $dn, string $attribute, string $value) : CompareQueryInterface
    {
        return $this->getAdapter()->createCompareQuery($dn, $attribute, $value);
    }

    protected static function checkFactoryClassArg($factoryClass, $method, $argno)
    {
        $msg_pre = "Invalid argument $argno to $method";
        if (!class_exists($factoryClass)) {
            $msg = $msg_pre . ": $factoryClass is not a name of existing class";
            throw new InvalidArgumentException($msg);
        }
        if (!is_subclass_of($factoryClass, AdapterFactoryInterface::class)) {
            $msg = $msg_pre . ": $factoryClass is not an implementation of ". AdapterFactoryInterface::class;
            throw new InvalidArgumentException($msg);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
