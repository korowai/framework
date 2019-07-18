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
class DnSpec extends Terminal
{
    protected $dn;

    public function __construct(string $dn, string $code=null, int $start=null, int $end=null)
    {
        $this->init($dn, $code, $start, $end);
    }

    public function init(string $dn, string $code=null, string $start=null, string $end=null)
    {
        $this->initTerminal($code ?? "dn: $dn", $start, $end);
        $this->dn = $dn;
        return $this;
    }

    /**
     * Returns the stored DN.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->dn;
    }

    /**
     * Returns this node's type as a string.
     *
     * @return string
     */
    public function getAstType() : string
    {
        return 'dn-spec';
    }
}

// vim: syntax=php sw=4 ts=4 et:
