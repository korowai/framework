<?php
/**
 * @file Testing/FrameworkDetails.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing;

use Korowai\Testing\Traits\PackageDetailsMemberArrays;
use Korowai\Testing\Traits\PackageDetailsComposite;
use Korowai\Testing\Traits\PackageClassesDetails;
use Korowai\Lib\Basic\Singleton;

/**
 * Describes expected details of all Korowai packages altogether.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class FrameworkDetails implements PackageDetailsInterface
{
    use Singleton;
    use PackageDetailsMemberArrays;
    use PackageDetailsComposite;
    use PackageClassesDetails;

    protected $packageDetailsRegistry = [
        'korowai\contracts'     => Contracts\PackageDetails::class,
        'korowai\ldaplib'       => Lib\Ldap\PackageDetails::class,
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
