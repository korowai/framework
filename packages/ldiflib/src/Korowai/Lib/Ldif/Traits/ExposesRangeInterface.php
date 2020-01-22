<?php
/**
 * @file src/Korowai/Lib/Ldif/Traits/ExposesRangeInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\RangeInterface;
use Korowai\Lib\Ldif\LocationInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ExposesRangeInterface
{
    use ExposesLocationInterface;

    abstract public function getRange() : ?RangeInterface;

    /**
     * Returns the CouledLocationInterface instance as required by ExposesLocationInterface.
     *
     * @return CouledLocationInterface|null
     */
    public function getLocation() : ?LocationInterface
    {
        return $this->getRange();
    }

    /**
     * {@inheritdoc}
     */
    public function getLength() : int
    {
        return $this->getRange()->getLength();
    }

    /**
     * {@inheritdoc}
     */
    public function getEndOffset() : int
    {
        return $this->getRange()->getEndOffset();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceLength() : int
    {
        return $this->getRange()->getSourceLength();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceEndOffset() : int
    {
        return $this->getRange()->getSourceEndOffset();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceCharLength(string $encoding = null) : int
    {
        return $this->getRange()->getSourceCharLength(...(func_get_args()));
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceCharEndOffset(string $encoding = null) : int
    {
        return $this->getRange()->getSourceCharEndOffset(...(func_get_args()));
    }
}

// vim: syntax=php sw=4 ts=4 et:
