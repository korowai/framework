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

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\BindingTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\EntryManagerTrait;

//use Korowai\Lib\Ldap\AdapterInterface;
//use Korowai\Lib\Ldap\AdapterFactoryInterface;
//use Korowai\Lib\Ldap\BindingInterface;
//use Korowai\Lib\Ldap\EntryManagerInterface;
//use Korowai\Lib\Ldap\SearchQueryInterface;
//use Korowai\Lib\Ldap\CompareQueryInterface;
//use Korowai\Lib\Ldap\ResultInterface;

use InvalidArgumentException;

/**
 * @todo Write documentation
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class Ldap implements LdapInterface, LdapLinkWrapperInterface
{
    use LdapLinkWrapperTrait;
    use BindingTrait;
    use EntryManagerTrait;

//    /** @var AdapterInterface */
//    private $adapter;
//
//    /**
//     * @todo Write documentation
//     *
//     * @param  array $config
//     * @param  string $factoryClass
//     *
//     * @return Ldap
//     * @throws InvalidArgumentException
//     */
//    public static function createWithConfig(array $config = [], string $factoryClass = null)
//    {
//        if (!isset($factoryClass)) {
//            $factoryClass = static::$defaultAdapterFactory;
//        } else {
//            static::checkFactoryClassArg($factoryClass, __METHOD__, 2);
//        }
//        $factory = new $factoryClass();
//        $factory->configure($config);
//        return static::createWithAdapterFactory($factory);
//    }
//
//    /**
//     * Returns new Ldap instance with adapter created by *$factory*.
//     *
//     * @param  AdapterFactoryInterface $factory
//     * @return Ldap
//     */
//    public static function createWithAdapterFactory(AdapterFactoryInterface $factory)
//    {
//        $adapter = $factory->createAdapter();
//        return new static($adapter);
//    }

    /**
     * Create new Ldap instance
     *
     * @param LdapLinkInterface $ldapLink
     * @param bool $bound
     */
    public function __construct(LdapLinkInterface $ldapLink, bool $bound = false)
    {
        $this->ldapLink = $ldapLink;
        $this->bound = $bound;
    }

//    /**
//     * {@inheritdoc}
//     */
//    public function bind(string $dn = null, string $password = null) : bool
//    {
//        $args = @func_get_args();
//        return $this->getBinding()->bind(...$args);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function unbind() : bool
//    {
//        return $this->getBinding()->unbind();
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function isBound() : bool
//    {
//        return $this->getBinding()->isBound();
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function add(EntryInterface $entry) : void
//    {
//        $this->getEntryManager()->add($entry);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function update(EntryInterface $entry) : void
//    {
//        $this->getEntryManager()->update($entry);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function rename(EntryInterface $entry, string $newRdn, bool $deleteOldRdn = true) : void
//    {
//        $this->getEntryManager()->rename($entry, $newRdn, $deleteOldRdn);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function delete(EntryInterface $entry) : void
//    {
//        $this->getEntryManager()->delete($entry);
//    }
//
    /**
     * {@inheritdoc}
     */
    public function createSearchQuery(string $base_dn, string $filter, array $options = []) : SearchQueryInterface
    {
        return new SearchQuery($this->getLdapLink(), $base_dn, $filter, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function createCompareQuery(string $dn, string $attribute, string $value) : CompareQueryInterface
    {
        return new CompareQuery($this->getLdapLink(), $dn, $attribute, $value);
    }

//    protected static function checkFactoryClassArg($factoryClass, $method, $argno)
//    {
//        $msg_pre = "Invalid argument $argno to $method";
//        if (!class_exists($factoryClass)) {
//            $msg = $msg_pre . ": $factoryClass is not a name of existing class";
//            throw new InvalidArgumentException($msg);
//        }
//        if (!is_subclass_of($factoryClass, AdapterFactoryInterface::class)) {
//            $msg = $msg_pre . ": $factoryClass is not an implementation of ". AdapterFactoryInterface::class;
//            throw new InvalidArgumentException($msg);
//        }
//    }

    /**
     * Create search query, execute and return its result
     *
     * @param  string $base_dn
     * @param  string $filter
     * @param  array $options
     *
     * @return ResultInterface Query result
     */
    public function search(string $base_dn, string $filter, array $options = []) : ResultInterface
    {
        return $this->createSearchQuery($base_dn, $filter, $options)->getResult();
    }

    /**
     * Create compare query, execute and return its result
     *
     * @param  string $dn
     * @param  string $attribute
     * @param  string $value
     *
     * @return bool Result of the comparison
     */
    public function compare(string $dn, string $attribute, string $value) : bool
    {
        return $this->createCompareQuery($dn, $attribute, $value)->getResult();
    }
}

// vim: syntax=php sw=4 ts=4 et:
