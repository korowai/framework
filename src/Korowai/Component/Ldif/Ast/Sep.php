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
 * Represents LDIF separator SEP ("\n" or "\r\n").
 */
class Sep extends Terminal
{
    public function __construct(string $code=null, int $start=null, int $end=null)
    {
        $this->init($code, $start, $end);
    }

    public function init(string $code=null, int $start=null, int $end=null)
    {
        $this->initTerminal($code ?? "\n", $start, $end);
    }

    public function getValue()
    {
        return null;
    }

    public function getAstType() : string
    {
        return 'SEP';
    }
}

// vim: syntax=php sw=4 ts=4 et:
