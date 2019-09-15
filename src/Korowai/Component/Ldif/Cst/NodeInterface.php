<?php
/**
 * @file src/Korowai/Component/Ldif/Cst/NodeInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif\Cst;

use \Korowai\Component\Ldif\CoupledLocationInterface;

/**
 * LDIF Concrete Syntax Tree Node.
 */
interface NodeInterface
{
    /**
     * Returns node's CST type corresponding to BNF symbol from RFC2849.
     *
     * @return string
     */
    public function getNodeType() : string;

    /**
     * Returns a location pointing at the beginning of the node.
     *
     * @return CoupledLocationInterface
     */
    public function getLocation() : CoupledLocationInterface;

    /**
     * Returns the string length of the substring that makes up the node.
     *
     * @return int
     */
    public function getStrlen() : int;

    /**
     * Returns the string length of the source substring that makes up the node.
     *
     * @return int
     */
    public function getSourceStrlen() : int;

    /**
     * Returns the string length of the substring that makes up the node.
     *
     * @return int
     */
    public function getSubstr() : string;

    /**
     * Returns the string length of the source substring that makes up the node.
     *
     * @return int
     */
    public function getSourceSubstr() : string;

    /**
     * Returns array of child nodes.
     *
     * If the node has no children, returns empty array.
     *
     * @return array
     */
    public function getChildren() : array;

    /**
     * Returns true, if the node encapsulates value.
     *
     * @return bool
     */
    public function hasValue() : bool;

    /**
     * Returns the semantic value encapsulated by node.
     *
     * @return mixed
     */
    public function getValue();
}

// vim: syntax=php sw=4 ts=4 et:
