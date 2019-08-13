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

use \Korowai\Component\Ldif\CoupledLocationInterface;

/**
 * LDIF Concrete Syntax Tree Node.
 */
abstract class AbstractNode implements NodeInterface
{
    /**
     * @var CoupledLocationInterface
     */
    protected $location;

    protected function initAbstractNode(CoupledLocationInterface $location)
    {
        $this->location = $location;
    }

    /**
     * Initializes the object.
     */
    public function __construct(CoupledLocationInterface $location)
    {
        $this->initAbstractNode($location);
    }

    /**
     * {@inheritdoc}
     */
    public function getLocation() : CoupledLocationInterface
    {
        return $this->location;
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
