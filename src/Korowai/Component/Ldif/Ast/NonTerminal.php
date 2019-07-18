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

abstract class NonTerminal extends AbstractNode
{
    /**
     * @var NodeInterface[]
     */
    protected $children;


    /**
     * Returns the zero-base line number of the line, where this AST node
     * starts in the source code.
     *
     * @return int
     */
    public function getStartLine() : int
    {
        $children = $this->getChildren();
        if(!count($children)) {
            return -1;
        }
        $first = reset($children);
        return $first->getStartLine();
    }


    /**
     * Returns the zero-based line number of the line, where this AST node ends
     * in the source code.
     *
     * @return int
     */
    public function getEndLine() : int
    {
        $children = $this->getChildren();
        if(!count($children)) {
            return -1;
        }
        $last = end($children);
        return $last->getEndLine();
    }

    /**
     * Returns direct child nodes of this node.
     *
     * @return array
     */
    public function getChildren() : array
    {
        return $this->children ?? [];
    }

    /**
     * Add child node.
     *
     * @return int
     */
    public function addChild(NodeInterface $ast)
    {
        if(!isset($this->children)) {
            $this->children = [];
        }
        array_push($this->children, $ast);
        return $this;
    }


    /**
     * Generates the LDIF code represented by this AST node ant its children.
     *
     * @return string
     */
    public function getCode() : string
    {
        $children = $this->getChildren();
        $pieces = array_map(function($n) {return $n->getCode();}, $children);
        return implode($pieces);
    }
}

// vim: syntax=php sw=4 ts=4 et:
