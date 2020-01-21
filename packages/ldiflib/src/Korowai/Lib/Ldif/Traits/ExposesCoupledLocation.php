<?php
/**
 * @file src/Korowai/Lib/Ldif/Traits/ExposesCoupledLocation.php
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

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ExposesCoupledLocation
{
    use ExposesSourceLocation;

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
}

// vim: syntax=php sw=4 ts=4 et:
