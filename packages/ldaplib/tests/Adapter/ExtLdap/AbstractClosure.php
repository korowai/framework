<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter\ExtLdap;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractClosure
{
    /**
     * @var mixed
     */
    private $returnValue;

    /**
     * @var array
     */
    private $returnArguments;

    /**
     * Initializes the object
     *
     * @param  mixed $returnValue
     * @param  array|null $returnArguments
     */
    public function __construct($returnValue, array $returnArguments)
    {
        $this->returnValue = $returnValue;
        $this->returnArguments = $returnArguments;
    }

    /**
     * @return mixed
     */
    protected function getReturnValue()
    {
        return $this->returnValue;
    }

    /**
     * Returns the array of arguments to be set to parameters passed via reference.
     *
     * @return array|null
     */
    protected function getReturnArguments() : ?array
    {
        return $this->returnArguments;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
