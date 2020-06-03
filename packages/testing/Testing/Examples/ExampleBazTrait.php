<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/testing
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Examples;

/**
 * Example interface for testing purposes.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ExampleBazTrait
{
    private $baz;

    /**
     * Sets value of the *$baz* attribute.
     *
     * @param  mixed $baz
     *      New attribute value.
     * @return object
     *      Returns *$this*.
     */
    public function setBaz($baz)
    {
        $this->baz = $baz;
        return $this;
    }

    /**
     * Returns *$baz* attribute.
     * @return mixed
     */
    public function getBaz()
    {
        return $this->baz;
    }
}

// vim: syntax=php sw=4 ts=4 et:
