<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap;

/**
 * An exception resulted form converting PHP.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ErrorExceptionInterface extends ExceptionInterface
{
    public function getSeverity();
}

// vim: syntax=php sw=4 ts=4 et tw=119:
