<?php
/**
 * @file src/Korowai/Lib/Ldif/Traits/ExposesSourceLocation.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\SourceLocationInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ExposesSourceLocation
{
    abstract public function getSourceLocation() : ?SourceLocationInterface;

    /**
     * {@inheritdoc}
     */
    public function getSourceFileName() : string
    {
        return $this->getSourceLocation()->getSourceFileName();
    }


    /**
     * {@inheritdoc}
     */
    public function getSourceString() : string
    {
        return $this->getSourceLocation()->getSourceString();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceByteOffset() : int
    {
        return $this->getSourceLocation()->getSourceByteOffset();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceCharOffset(string $encoding = null) : int
    {
        return $this->getSourceLocation()->getSourceCharOffset(...(func_get_args()));
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceLineIndex() : int
    {
        return $this->getSourceLocation()->getSourceLineIndex();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceLine(int $index = null) : string
    {
        return $this->getSourceLocation()->getSourceLine(...func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceLineAndByteOffset() : array
    {
        return $this->getSourceLocation()->getSourceLineAndByteOffset();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceLineAndCharOffset(string $encoding = null) : array
    {
        return $this->getSourceLocation()->getSourceLineAndCharOffset(...(func_get_args()));
    }
}

// vim: syntax=php sw=4 ts=4 et:
