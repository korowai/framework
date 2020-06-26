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
use PHPUnit\Framework\Constraint\IsIdentical;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait MakeArgsForLdapFunctionMock
{
    abstract public static function identicalTo($value) : IsIdentical;

    private static function makeArgsForLdapFunctionMock(array $resources, array $args) : array
    {
        $resources = array_map(function ($resource) {
            return ($resource instanceof ResourceWrapperInterface) ? $resource->getResource() : $resource;
        }, $resources);
        return array_map([static::class, 'identicalTo'], array_merge($resources, $args));
    }
}

// vim: syntax=php sw=4 ts=4 et:
