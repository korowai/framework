<?php
/**
 * @file Testing/Contracts/ErrorLibDetails.php
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

// Korowai\Lib\Error
use Korowai\Lib\Error\ErrorHandlerInterface;

/**
 * Describes expected details or the korowai\contracts package.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ErrorLibDetails implements PackageDetailsInterface
{
    use PackageDetailsMemberArrays;
    use Singleton;

    protected $classesDetails = [
        // Korowai\Lib\Error
        ErrorHandlerInterface::class        => [
            'interfaces'                    => [],
            'properties'                    => [
                'errorTypes'                => 'getErrorTypes'
            ],
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
