<?php
/**
 * @file src/Korowai/Lib/Ldif/Traits/DecoratesRangeInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\RangeInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait DecoratesRangeInterface
{
    use ExposesRangeInterface;

    /**
     * @var RangeInterface
     */
    protected $range;

    /**
     * Sets instance of RangeInterface to this wrapper.
     *
     * @return $this
     */
    public function setRange(RangeInterface $range)
    {
        $this->range = $range;
        return $this;
    }

    /**
     * Returns the encapsulated instance of RangeInterface.
     *
     * @return RangeInterface|null
     */
    public function getRange() : ?RangeInterface
    {
        return $this->range;
    }
}

// vim: syntax=php sw=4 ts=4 et:
