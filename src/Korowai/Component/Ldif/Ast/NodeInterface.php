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

interface NodeInterface
{
    /**
     * Returns direct child nodes of this node.
     *
     * @return array
     */
    public function getChildren() : array;

    /**
     * Returns this node's type as a string.
     *
     * @return string
     */
    public function getAstType() : string;

    /**
     * Retuns the semantic value assigned to this AST node.
     */
    public function getValue();

    /**
     * Returns the zero-base line number of the line, where this AST node
     * starts in the source code.
     *
     * @return int
     */
    public function getStartLine() : int;

    /**
     * Returns the zero-based line number of the line, where this AST node ends
     * in the source code.
     *
     * @return int
     */
    public function getEndLine() : int;

    /**
     * Generates the LDIF code represented by this AST node and its children.
     *
     * @return string
     */
    public function getCode() : string;

    /**
     * Same as getCode()
     *
     * @return string
     */
    public function __toString();
}

// vim: syntax=php sw=4 ts=4 et:
