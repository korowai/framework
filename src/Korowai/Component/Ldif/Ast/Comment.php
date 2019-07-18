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
 * Represents a single comment line (RFC2849)
 */
class Comment extends Terminal
{
    protected $text;

    public function __construct(string $text, string $code=null, int $start=null, int $end=null)
    {
        $this->init($text, $code, $start, $end);
    }

    public function init(string $text, string $code=null, int $start=null, int $end=null)
    {
        $this->initTerminal($code ?? "# $text", $start, $end);
        $this->text = $text;
        return $this;
    }

    /**
     * Returns the stored comment text.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->text;
    }

    /**
     * Returns this node's type as a string.
     *
     * @return string
     */
    public function getAstType() : string
    {
        return 'comment';
    }
}

// vim: syntax=php sw=4 ts=4 et:
