<?php
/**
 * @file tests/Korowai/Lib/Ldap/Adapter/ResultReferenceInterfaceTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ResultReferenceInterfaceTrait
{
    use ResultRecordInterfaceTrait;

    public $referrals;

    public function getReferrals() : array
    {
        return $this->referrals;
    }
}

// vim: syntax=php sw=4 ts=4 et:
