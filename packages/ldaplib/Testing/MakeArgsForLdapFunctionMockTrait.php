<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Ldaplib;

use Korowai\Lib\Ldap\Adapter\ExtLdap\ResourceWrapperInterface;
use PHPUnit\Framework\Constraint\IsIdentical;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait MakeArgsForLdapFunctionMockTrait
{
    abstract public static function identicalTo($value) : IsIdentical;

    /**
     * Prepares an array of PHPUnit constraints that describe expected
     * arguments to a mocked LDAP function.
     *
     * @param array $resources
     * @param array $args
     *
     * @return array
     */
    private static function makeArgsForLdapFunctionMock(array $resources, array $args) : array
    {
        $resources = array_map(function ($resource) {
            return ($resource instanceof ResourceWrapperInterface) ? $resource->getResource() : $resource;
        }, $resources);
        return array_map([static::class, 'identicalTo'], array_merge($resources, $args));
    }
}

// vim: syntax=php sw=4 ts=4 et:
