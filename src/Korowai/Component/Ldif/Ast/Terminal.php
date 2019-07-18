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

abstract class Terminal extends AbstractNode
{
    /**
     * @var string
     */
    protected $code;
    /**
     * @var int
     */
    protected $startLine = -1;
    /**
     * @var int
     */
    protected $endLine = -1;

    /**
     * Returns child nodes of this node.
     *
     * @return array
     */
    public function getChildren() : array
    {
        return [];
    }

    /**
     * Generates LDIF code represented by this AST node.
     *
     * @return string
     */
    public function getCode() : string
    {
        return $this->code;
    }

    /**
     * Returns the zero-base line number of the line, where this AST node
     * starts in the source code.
     *
     * @return int
     */
    public function getStartLine() : int
    {
        return $this->startLine;
    }

    /**
     * Returns the zero-based line number of the line, where this AST node ends
     * in the source code.
     *
     * @return int
     */
    public function getEndLine() : int
    {
        return $this->endLine;
    }

    /**
     * Sets the start and end line numbers.
     *
     * @param int $startLine
     * @param int $endLine
     */
    protected function initTerminal(string $code, int $startLine=null, int $endLine=null)
    {
        $this->code = $code;
        $this->startLine = $startLine ?? -1;
        $this->endLine = $endLine ?? $startLine;
    }
}

// vim: syntax=php sw=4 ts=4 et:
