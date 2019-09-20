<?php
/**
 * @file src/Korowai/Component/Ldif/Cst/AbstractNode.php
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
abstract class AbstractNode implements NodeInterface
{
    /**
     * @var CoupledLocationInterface
     */
    protected $location;

    /**
     * @var int
     */
    protected $strlen;

    protected function initAbstractNode(CoupledLocationInterface $location, int $strlen)
    {
        $this->location = $location;
        $this->strlen = $strlen;
    }

    /**
     * Initializes the object.
     *
     * @param CoupledLocationInterface $location
     * @param int $strlen
     */
    public function __construct(CoupledLocationInterface $location, int $strlen)
    {
        $this->initAbstractNode($location, $strlen);
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
    public function getStrlen() : int
    {
        return $this->strlen;
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceStrlen() : int
    {
        return strlen($this->getSourceSubstr());
    }

    /**
     * {@inheritdoc}
     */
    public function getSubstr() : string
    {
        $location = $this->getLocation();
        $input = $location->getInput();
        $begin = $location->getByteOffset();
        $strlen = $this->getStrlen();
        return substr($input->getString(), $begin, $strlen);
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceSubstr() : string
    {
        $location = $this->getLocation();
        $input = $location->getInput();

        $substr = $this->getSubstr();

        $mb_strlen = mb_strlen($substr);
        $substr_1 = mb_substr($substr, 0, max($mb_strlen-1, 0));
        $strlen_1 = strlen($substr_1);
        $end = $location->getByteOffset() + $strlen_1;

        $source = $location->getSourceString();
        $mb_srcbeg = $location->getSourceCharOffset();
        $mb_srcend = $input->getSourceCharOffset($end);

        return mb_substr($source, $mb_srcbeg, 1 + $mb_srcend - $mb_srcbeg);
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

    protected function getLastCharOffset() : int
    {
        $input = $this->getLocation()->getInput();
        $offset = $this->getLocation()->getByteOffset();
        $substr = $this->getSubstr();
        $mb_strlen = mb_strlen($substr);
        $substr_1 = mb_substr($substr, 0, max($mb_strlen-1, 0));
        return strlen($substr_1);
    }
}

// vim: syntax=php sw=4 ts=4 et:
