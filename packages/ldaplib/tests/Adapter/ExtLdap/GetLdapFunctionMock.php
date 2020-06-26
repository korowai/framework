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

use Korowai\Lib\Ldap\Adapter\ExtLdap\ResourceWrapperInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait GetLdapFunctionMock
{
    abstract public function getFunctionMock(string $namespace, string $name);

    private function getLdapFunctionMock(string $name)
    {
        return $this->getFunctionMock('\Korowai\Lib\Ldap\Adapter\ExtLdap', $name);
    }
}

// vim: syntax=php sw=4 ts=4 et:
