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
        // FIXME: validate/resolve?
        $this->config = $config;
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
        throw new \BadMethodCallException('not implemented')
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    public function port() : int
    {
        throw new \BadMethodCallException('not implemented')
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    public function encryption() : string
    {
        throw new \BadMethodCallException('not implemented')
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    public function uri() : string
    {
        throw new \BadMethodCallException('not implemented')
    }

//    /**
//     * {@inheritdoc}
//     *
//     * @psalm-mutation-free
//     */
//    public function options() : array
//    {
//        throw new \BadMethodCallException('not implemented')
//    }
}

// vim: syntax=php sw=4 ts=4 et:
