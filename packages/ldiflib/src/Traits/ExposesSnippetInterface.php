<?php
/**
 * @file src/Korowai/Lib/Ldif/Traits/ExposesSnippetInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\LocationInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ExposesSnippetInterface
{
    use ExposesLocationInterface;

    abstract public function getSnippet() : ?SnippetInterface;

    /**
     * Returns the CouledLocationInterface instance as required by ExposesLocationInterface.
     *
     * @return CouledLocationInterface|null
     */
    public function getLocation() : ?LocationInterface
    {
        return $this->getSnippet();
    }

    /**
     * {@inheritdoc}
     */
    public function getLength() : int
    {
        return $this->getSnippet()->getLength();
    }

    /**
     * {@inheritdoc}
     */
    public function getEndOffset() : int
    {
        return $this->getSnippet()->getEndOffset();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceLength() : int
    {
        return $this->getSnippet()->getSourceLength();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceEndOffset() : int
    {
        return $this->getSnippet()->getSourceEndOffset();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceCharLength(string $encoding = null) : int
    {
        return $this->getSnippet()->getSourceCharLength(...(func_get_args()));
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceCharEndOffset(string $encoding = null) : int
    {
        return $this->getSnippet()->getSourceCharEndOffset(...(func_get_args()));
    }
}

// vim: syntax=php sw=4 ts=4 et:
