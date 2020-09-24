<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap;

use Korowai\Lib\Ldap\CompareQueryInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ComparingInterfaceTrait
{
    public $compare;
    public $createCompareQuery;

    public function compare(string $dn, string $attribute, string $value): bool
    {
        return $this->compare;
    }

    public function createCompareQuery(string $dn, string $attribute, string $value): CompareQueryInterface
    {
        return $this->createCompareQuery;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
