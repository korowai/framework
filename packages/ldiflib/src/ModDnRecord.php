<?php
/**
 * @file src/ModDnRecord.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

use Korowai\Lib\Ldif\Traits\HasAttrValSpecs;

/**
 * Represents [RFC2849](https://tools.ietf.org/html/rfc2849)
 * ldif-change-record of type change-add.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ModDnRecord extends AbstractRecord implements ModDnRecordInterface
{
    private $modDnType;

    /**
     * Initializes the object.
     *
     * @param  SnippetInterface $snippet
     * @param  string $dn
     * @param  array $modDnType
     * @param  array $newRdn
     */
    public function __construct(SnippetInterface $snippet, string $dn, string $modDnType, string $newRdn)
    {
        parent::initAbstractRecord($snippet, $dn);
        $this->setModDnType($modDnType);
        $this->setNewRdn($newRdn);
    }

    /**
     * Sets the type of this moddn operation.
     *
     * @param  string $modDnType
     * @return object $this
     */
    public function setModDnType(string $modDnType)
    {
        $this->modDnType = $modDnType;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getModDnType() : string
    {
        return $this->modDnType;
    }

    /**
     * Sets the type of this moddn operation.
     *
     * @param  string $newRdn
     * @return object $this
     */
    public function setNewRdn(string $newRdn)
    {
        $this->newRdn = $newRdn;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewRdn() : string
    {
        return $this->newRdn;
    }

    /**
     * {@inheritdoc}
     */
    public function acceptRecordVisitor(RecordVisitorInterface $visitor)
    {
        return $visitor->visitModDnRecord($this);
    }
}

// vim: syntax=php sw=4 ts=4 et:
