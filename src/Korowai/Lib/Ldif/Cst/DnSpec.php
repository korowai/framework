<?php
/**
 * @file src/Korowai/Lib/Ldif/Cst/DnSpec.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Cst;

use \Korowai\Lib\Ldif\CoupledLocationInterface;

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

    public function __construct(CoupledLocationInterface $location, int $strlen, string $dn)
    {
        $this->initDnSpec($location, $strlen, $dn);
    }

    protected function initDnSpec(CoupledLocationInterface $location, int $strlen, string $dn)
    {
        $this->initAbstractNode($location, $strlen);
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
