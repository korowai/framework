<?php
/**
 * @file src/Korowai/Lib/Ldif/Records/VersionSpec.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Records;

use Korowai\Lib\Ldif\RangeInterface;
use Korowai\Lib\Ldif\RecordVisitorInterface;

/**
 * The version-spec ([RFC 2849](https://tools.ietf.org/html/rfc2849)) record
 * containing LDIF version number.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class VersionSpec extends AbstractRecord implements VersionSpecInterface
{
    /**
     * @var int
     */
    protected $version;

    /**
     * Initializes the object.
     *
     * @param  RangeInterface $range
     * @param  int $version
     */
    public function __construct(RangeInterface $range, int $version)
    {
        $this->initAbstractRecord($range);
        $this->setVersion($version);
    }

    /**
     * Returns the version number stored as semantic value.
     *
     * @return object $this
     */
    public function setVersion(int $version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion() : int
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function acceptRecordVisitor(RecordVisitorInterface $visitor)
    {
        return $visitor->visitVersionSpec($this);
    }
}

// vim: syntax=php sw=4 ts=4 et:
