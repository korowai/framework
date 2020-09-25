<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Basic;

/**
 * An interface implemented by singleton classes.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface SingletonInterface
{
    /**
     * Fetch an instance of the class.
     *
     * @return object
     */
    public static function getInstance();
}

// vim: syntax=php sw=4 ts=4 et:
