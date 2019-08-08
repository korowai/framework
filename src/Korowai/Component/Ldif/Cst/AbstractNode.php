<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif\Cst;

use \Korowai\Component\Ldif\CursorInterface;

/**
 * LDIF Concrete Syntax Tree Node.
 */
abstract class AbstractNode implements NodeInterface
{
    /**
     * @var CursorInterface
     */
    protected $cursor;

    protected function initAbstractNode(CursorInterface $cursor)
    {
        $this->cursor = $cursor;
    }

    /**
     * Initializes the object.
     */
    public function __construct(CursorInterface $cursor)
    {
        $this->initAbstractNode($cursor);
    }

    /**
     * {@inheritdoc}
     */
    public function getCursor() : CursorInterface
    {
        return $this->cursor;
    }

    /**
     * {@inheritdoc}
     */
    public function hasValue() : bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return null;
    }
}

// vim: syntax=php sw=4 ts=4 et:
