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

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait HasResult
{
    /**
     * @var ExtLdapResultInterface
     */
    private $result;

    /**
     * Returns the encapsulated Result instance.
     *
     * @return ExtLdapResultInterface
     */
    public function getResult() : ExtLdapResultInterface
    {
        return $this->result;
    }

    /**
     * Sets the ResultInterface instance to this object.
     *
     * @param ExtLdapResultInterface $result
     */
    protected function setResult(ExtLdapResultInterface $result)
    {
        $this->result = $result;
    }
}

// vim: syntax=php sw=4 ts=4 et:
