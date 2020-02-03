<?php
/**
 * @file src/Traits/ExposesLocationInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\SourceLocationInterface;
use Korowai\Lib\Ldif\InputInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ExposesLocationInterface
{
    use ExposesSourceLocationInterface;

    abstract public function getLocation() : ?LocationInterface;

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
    public function getOffset() : int
    {
        return $this->getLocation()->getOffset();
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
    public function getInput() : InputInterface
    {
        return $this->getLocation()->getInput();
    }
}

// vim: syntax=php sw=4 ts=4 et:
