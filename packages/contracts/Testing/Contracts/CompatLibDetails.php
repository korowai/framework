<?php
/**
 * @file Testing/Contracts/CompatLibDetails.php
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

/**
 * Describes interfaces defined by korowai\contracts for korowai\compatlib.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class CompatLibDetails implements PackageDetailsInterface
{
    use PackageDetailsMemberArrays;
    use Singleton;

    protected $classesDetails = [
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
