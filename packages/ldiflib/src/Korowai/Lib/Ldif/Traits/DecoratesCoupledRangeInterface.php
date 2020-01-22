<?php
/**
 * @file src/Korowai/Lib/Ldif/Traits/DecoratesCoupledRangeInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\CoupledRangeInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait DecoratesCoupledRangeInterface
{
    use ExposesCoupledRangeInterface;

    /**
     * @var CoupledRangeInterface
     */
    protected $range;

    /**
     * Sets instance of CoupledRangeInterface to this wrapper.
     *
     * @return $this
     */
    public function setRange(CoupledRangeInterface $range)
    {
        $this->range = $range;
        return $this;
    }

    /**
     * Returns the encapsulated instance of CoupledRangeInterface.
     *
     * @return CoupledRangeInterface|null
     */
    public function getRange() : ?CoupledRangeInterface
    {
        return $this->range;
    }
}

// vim: syntax=php sw=4 ts=4 et:
