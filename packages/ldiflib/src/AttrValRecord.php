<?php
/**
 * @file src/AttrValRecord.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

/**
 * Represents [RFC2849](https://tools.ietf.org/html/rfc2849)
 * ldif-attrval-record.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AttrValRecord extends AbstractRecord implements AttrValRecordInterface
{
    /**
     * @var array
     */
    protected $attrValSpecs;

    /**
     * Initializes the object.
     *
     * @param SnippetInterface $snippet
     * @param string $dn
     * @param array $attrValSpecs
     */
    public function __construct(SnippetInterface $snippet, string $dn, array $attrValSpecs)
    {
        parent::initAbstractRecord($snippet, $dn);
        $this->setAttrValSpecs($attrValSpecs);
    }

    /**
     * Sets the new array of attribute value specifications to this record.
     *
     * @param array $attrValSpec
     *
     * @return object $this
     */
    public function setAttrValSpecs(array $attrValSpecs)
    {
        $this->attrValSpecs = $attrValSpecs;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttrValSpecs() : array
    {
        return $this->attrValSpecs;
    }

    /**
     * {@inheritdoc}
     */
    public function acceptRecordVisitor(RecordVisitorInterface $visitor)
    {
        return $visitor->visitAttrValRecord($this);
    }
}

// vim: syntax=php sw=4 ts=4 et:
