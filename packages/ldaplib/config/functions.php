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
 * Returns a path to a config provided by korowai/ldaplib
 *
 * @param string $filename
 * @return string
 */
function config_path(string $filename = '') : string
{
    $path = __dir__;
    if ($filename) {
        $path .= '/'.ltrim($filename, '/');
    }
    return $path;
}

// vim: syntax=php sw=4 ts=4 et tw=119:
