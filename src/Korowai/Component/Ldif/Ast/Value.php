<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif\Ast;

/**
 * Represents dn-spec (RFC2849)
 */
class Value
{
    const SAFE_STRING   = 0; // ''
    const BASE64_STRING = 1; // ':'
    const URL           = 2; // '<'

    const DEFAULT_KIND  = 0; // SAFE_STRING

    static protected $codePrefixes = ['', ':', '<'];

    protected $value;
    protected $kind;

    public function __construct(string $value, int $kind=null)
    {
        $this->value = $value;
        $this->kind = $kind ?? self::DEFAULT_KIND;
    }

    public function get()
    {
        return $this->value;
    }

    public function __toString()
    {
        return (string)$this->get();
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function getCode()
    {
        $prefix = self::$codePrefixes[$this->kind];
        return implode(' ', [$prefix, $this->value]);
    }
}

// vim: syntax=php sw=4 ts=4 et:
