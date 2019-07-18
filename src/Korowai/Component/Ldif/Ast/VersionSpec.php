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
 * Represents version-spec (RFC2849)
 */
class VersionSpec extends Terminal
{
    protected $version;

    public function __construct(int $version, string $code=null, int $start=null, int $end=null)
    {
        $this->init($version, $code, $start, $end);
    }

    public function init(int $version, string $code=null, int $start=null, int $end=null)
    {
        $this->initTerminal($code ?? "version: $version", $start, $end);
        $this->version = $version;
    }

    /**
     * Returns the stored version number.
     *
     * @return int
     */
    public function getValue()
    {
        return $this->version;
    }

    /**
     * Returns this node's type as a string.
     *
     * @return string
     */
    public function getAstType() : string
    {
        return 'version-spec';
    }
}

// vim: syntax=php sw=4 ts=4 et:
