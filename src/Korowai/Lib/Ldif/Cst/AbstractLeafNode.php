<?php
/**
 * @file src/Korowai/Lib/Ldif/Cst/AbstractLeafNode.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Cst;

/**
 * LDIF Concrete Syntax Tree Node.
 */
abstract class AbstractLeafNode extends AbstractNode
{
    /**
     * {@inheritdoc}
     */
    public function getChildren() : array
    {
        return [];
    }
}

// vim: syntax=php sw=4 ts=4 et:
