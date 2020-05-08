<?php
/**
 * @file src/DeleteRecord.php
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
 * *ldif-change-record* of type *change-delete*.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class DeleteRecord extends AbstractRecord implements DeleteRecordInterface
{
    /**
     * Initializes the object.
     *
     * @param  SnippetInterface $snippet
     * @param  string $dn
     * @param  array $attrValSpecs
     */
    public function __construct(SnippetInterface $snippet, string $dn)
    {
        parent::initAbstractRecord($snippet, $dn);
    }

    /**
     * {@inheritdoc}
     */
    public function getChangeType() : string
    {
        return "delete";
    }

    /**
     * {@inheritdoc}
     */
    public function acceptRecordVisitor(RecordVisitorInterface $visitor)
    {
        return $visitor->visitDeleteRecord($this);
    }
}

// vim: syntax=php sw=4 ts=4 et:
