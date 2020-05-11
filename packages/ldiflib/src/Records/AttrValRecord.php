<?php
/**
 * @file src/Records/AttrValRecord.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Records;

use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\RecordVisitorInterface;
use Korowai\Lib\Ldif\Traits\HasAttrValSpecs;

/**
 * Represents [RFC2849](https://tools.ietf.org/html/rfc2849)
 * ldif-attrval-record.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AttrValRecord extends AbstractRecord implements AttrValRecordInterface
{
    use HasAttrValSpecs;

    /**
     * Initializes the object.
     *
     * @param SnippetInterface $snippet
     * @param  string $dn
     * @param  array $attrValSpecs
     */
    public function __construct(SnippetInterface $snippet, string $dn, array $attrValSpecs)
    {
        parent::initAbstractRecord($snippet, $dn);
        $this->setAttrValSpecs($attrValSpecs);
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
