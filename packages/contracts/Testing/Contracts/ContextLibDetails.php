<?php
/**
 * @file Testing/Contracts/ContextLibDetails.php
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

// Korowai\Lib\Context
use Korowai\Lib\Context\ContextFactoryInterface;
use Korowai\Lib\Context\ContextFactoryStackInterface;
use Korowai\Lib\Context\ContextManagerInterface;
use Korowai\Lib\Context\ExecutorInterface;

/**
 * Describes expected details or the korowai\contracts package.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ContextLibDetails implements PackageDetailsInterface
{
    use PackageDetailsMemberArrays;
    use Singleton;

    protected $classesDetails = [
        // Korowai\Lib\Context
        ContextFactoryInterface::class      => [
            'interfaces'                    => [],
            'properties'                    => [],
        ],
        ContextFactoryStackInterface::class => [
            'interfaces'                    => [],
            'properties'                    => [
                'size'                      => 'size',
                'top'                       => 'top',
            ],
        ],
        ContextManagerInterface::class      => [
            'interfaces'                    => [],
            'properties'                    => [],
        ],
        ExecutorInterface::class            => [
            'interfaces'                    => [],
            'properties'                    => [],
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
