<?php
/**
 * @file src/Korowai/Lib/Ldif/Traits/ExposesCoupledLocationInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\CoupledLocationInterface;
use Korowai\Lib\Ldif\SourceLocationInterface;
use Korowai\Lib\Ldif\CoupledInputInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ExposesCoupledLocationInterface
{
    use ExposesSourceLocationInterface;

    abstract public function getLocation() : ?CoupledLocationInterface;

    /**
     * Returns the encapsulated instance of SourceLocationInterface.
     *
     * @return SourceLocationInterface
     */
    public function getSourceLocation() : ?SourceLocationInterface
    {
        return $this->getLocation();
    }

    /**
     * {@inheritdoc}
     */
    public function getString() : string
    {
        return $this->getLocation()->getString();
    }

    /**
     * {@inheritdoc}
     */
    public function getByteOffset() : int
    {
        return $this->getLocation()->getByteOffset();
    }

    /**
     * {@inheritdoc}
     */
    public function getCharOffset(string $encoding = null) : int
    {
        return $this->getLocation()->getCharOffset(...(func_get_args()));
    }

    /**
     * {@inheritdoc}
     */
    public function getInput() : CoupledInputInterface
    {
        return $this->getLocation()->getInput();
    }
}

// vim: syntax=php sw=4 ts=4 et:
