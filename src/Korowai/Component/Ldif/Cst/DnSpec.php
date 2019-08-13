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
 * dn-spec node (RFC 2849)
 */
class DnSpec extends AbstractLeafNode
{
    /**
     * @var int
     */
    protected $dn;

    /**
     * {@inheritdoc}
     */
    public function getNodeType() : string
    {
        return 'dn-spec';
    }

    public function __construct(CoupledLocationInterface $location, string $dn)
    {
        $this->initDnSpec($location, $dn);
    }

    protected function initDnSpec(CoupledLocationInterface $location, string $dn)
    {
        $this->initAbstractNode($location);
        $this->dn = $dn;
    }

    /**
     * {@inheritdoc}
     */
    public function hasValue() : bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->dn;
    }
}

// vim: syntax=php sw=4 ts=4 et:
