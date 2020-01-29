<?php
/**
 * @file Testing/Contracts/PackageDetails.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Contracts;

use Korowai\Lib\Basic\Singleton;
use Korowai\Testing\PackageDetailsInterface;
use Korowai\Testing\Traits\PackageDetailsMemberArrays;
use Korowai\Testing\Traits\PackageDetailsComposite;
use Korowai\Testing\Traits\PackageClassesDetails;

/**
 * Describes expected details or the korowai\contracts package.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class PackageDetails implements PackageDetailsInterface
{
    use PackageDetailsMemberArrays;
    use PackageDetailsComposite;
    use PackageClassesDetails;
    use Singleton;

    protected $packageDetailsRegistry = [
        'korowai/contracts@basiclib'      => BasicLibDetails::class,
        'korowai/contracts@compatlib'     => CompatLibDetails::class,
        'korowai/contracts@contextlib'    => ContextLibDetails::class,
        'korowai/contracts@ldaplib'       => LdapLibDetails::class,
        'korowai/contracts@ldiflib'       => LdifLibDetails::class,
        'korowai/contracts@testing'       => TestingDetails::class,
    ];

    /**
     * @todo Write documentation.
     */
    public function packagesDetails() : array
    {
        return array_map(function (string $class) {
            return call_user_func([$class, 'getInstance']);
        }, $this->packageDetailsRegistry);
    }
}

// vim: syntax=php sw=4 ts=4 et:
