<?php
/**
 * @file Testing/PackageDetails.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Lib\Ldap;

use Korowai\Lib\Ldap\AbstractLdap;
use Korowai\Lib\Ldap\Ldap;
use Korowai\Lib\Ldap\LdapInterface;
use Korowai\Lib\Ldap\Adapter\AdapterInterface;

use Korowai\Testing\PackageDetailsInterface;
use Korowai\Testing\Traits\PackageDetailsMemberArrays;

/**
 * Describes expected details or the korowai\ldaplib package.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class PackageDetails implements PackageDetailsInterface
{
    use PackageDetailsMemberArrays;

    protected $classesDetails = [
        Ldap::class      => [
            'parent'                        => AbstractLdap::class,
            //'interfaces'                    => [],
            //'properties'                    => [],
        ],
        AbstractLdap::class => [
            'interfaces'                    => [LdapInterface::class],
            //'properties'                    => [],
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function classesDetails() : array
    {
        return $this->classesDetails;
    }
}

// vim: syntax=php sw=4 ts=4 et:
