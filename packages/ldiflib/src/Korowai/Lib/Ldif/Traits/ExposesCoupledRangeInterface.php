<?php
/**
 * @file src/Korowai/Lib/Ldif/Traits/ExposesCoupledRangeInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\CoupledRangeInterface;
use Korowai\Lib\Ldif\CoupledLocationInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ExposesCoupledRangeInterface
{
    use ExposesCoupledLocationInterface;

    abstract public function getRange() : ?CoupledRangeInterface;

    /**
     * Returns the CouledLocationInterface instance as required by ExposesCoupledLocationInterface.
     *
     * @return CouledLocationInterface|null
     */
    public function getLocation() : ?CoupledLocationInterface
    {
        return $this->getRange();
    }

    /**
     * {@inheritdoc}
     */
    public function getByteLength() : int
    {
        return $this->getRange()->getByteLength();
    }

    /**
     * {@inheritdoc}
     */
    public function getByteEndOffset() : int
    {
        return $this->getRange()->getByteEndOffset();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceByteLength() : int
    {
        return $this->getRange()->getSourceByteLength();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceByteEndOffset() : int
    {
        return $this->getRange()->getSourceByteEndOffset();
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
