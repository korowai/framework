<?php
/**
 * @file src/Korowai/Lib/Ldif/Traits/DecoratesSourceLocationInterface.php
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
trait DecoratesSourceLocationInterface
{
    use ExposesSourceLocationInterface;

    /**
     * @var SourceLocationInterface
     */
    protected $location;

    /**
     * Sets the SourceLocationInterface instance to this object.
     *
     * @param SourceLocationInterface $location
     *
     * @return $this
     */
    public function setSourceLocation(SourceLocationInterface $location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Returns the cursor pointing at error location.
     *
     * @return SourceLocationInterface|null
     */
    public function getSourceLocation() : ?SourceLocationInterface
    {
        return $this->location;
    }
}

// vim: syntax=php sw=4 ts=4 et:
