<?php
/**
 * @file src/Korowai/Lib/Ldif/Traits/DecoratesCoupledLocationInterface.php
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

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait DecoratesCoupledLocationInterface
{
    use ExposesCoupledLocationInterface;

    /**
     * @var CoupledLocationInterface
     */
    protected $location;

    /**
     * Sets instance of CoupledLocationInterface to this wrapper.
     *
     * @return $this
     */
    public function setLocation(CoupledLocationInterface $location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Returns the encapsulated instance of CoupledLocationInterface.
     *
     * @return CoupledLocationInterface|null
     */
    public function getLocation() : ?CoupledLocationInterface
    {
        return $this->location;
    }
}

// vim: syntax=php sw=4 ts=4 et:
