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
 * version-spec node (RFC 2849)
 */
class VersionSpec extends AbstractLeafNode
{
    /**
     * @var int
     */
    protected $version;

    /**
     * {@inheritdoc}
     */
    public function getNodeType() : string
    {
        return 'version-spec';
    }

    public function __construct(CursorInterface $cursor, int $version)
    {
        $this->initVersionSpec($cursor, $version);
    }

    protected function initVersionSpec(CursorInterface $cursor, int $version)
    {
        $this->initAbstractNode($cursor);
        $this->version = $version;
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
        return $this->version;
    }
}

// vim: syntax=php sw=4 ts=4 et:
