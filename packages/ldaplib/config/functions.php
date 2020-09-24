<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Ldaplib;

/**
 * Returns a path to a config provided by korowai/ldaplib.
 */
function config_path(string $filename = ''): string
{
    return __DIR__.($filename ? '/'.ltrim($filename, '/') : '');
}

// vim: syntax=php sw=4 ts=4 et tw=119:
