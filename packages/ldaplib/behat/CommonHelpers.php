<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Behat;

trait CommonHelpers
{
    protected static function encryptForComparison(string $password, string $actual_password)
    {
        if (preg_match('/^\{([A-Z0-9]{3,5})\}(.+)$/', $actual_password, $matches)) {
            $tag = $matches[1];
            $actual_hash = $matches[2];
            if ('CRYPT' == strtoupper($tag)) {
                $hash = crypt($password, $actual_hash);
            } elseif ('MD5' == strtoupper($tag)) {
                $hash = base64_encode(md5($password, true));
            } elseif ('SHA1' == strtoupper($tag)) {
                $hash = base64_encode(sha1($password, true));
            } elseif ('SSHA' == strtoupper($tag)) {
                $salt = substr(base64_decode($actual_hash), 20);
                $hash = base64_encode(sha1($password.$salt, true).$salt);
            } else {
                throw new \RuntimeException("unsupported password hash format: {$tag}");
            }
            $password = '{'.$tag.'}'.$hash;
        }

        return $password;
    }
}

// vim: syntax=php sw=4 ts=4 et:
