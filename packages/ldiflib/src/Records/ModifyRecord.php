<?php
/**
 * @file src/Records/ModifyRecord.php
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
use Korowai\Lib\Ldif\Exception\InvalidChangeTypeException;

/**
 * Represents [RFC2849](https://tools.ietf.org/html/rfc2849)
 * *ldif-change-record* of type *change-modify*.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ModifyRecord extends AbstractRecord implements ModifyRecordInterface
{
    /**
     * @var string
     */
    private $attribute;

    /**
     * @var array
     */
    private $modSpecs;

    /**
     * Initializes the object.
     *
     * @param  SnippetInterface $snippet
     * @param  string $dn
     * @param  array $modSpecs
     */
    public function __construct(SnippetInterface $snippet, string $dn, array $modSpecs = [])
    {
        parent::initAbstractRecord($snippet, $dn);
        $this->setModSpecs($modSpecs);
    }

    /**
     * {@inheritdoc}
     */
    public function getChangeType() : string
    {
        return 'modify';
    }

    /**
     * Sets new array of [ModSpecInterface](\.\./ModSpecInterface.html) objects.
     *
     * @param  array $modSpecs
     * @return object $this
     */
    public function setModSpecs(array $modSpecs)
    {
        $this->modSpecs = $modSpecs;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getModSpecs() : array
    {
        return $this->modSpecs;
    }

    /**
     * {@inheritdoc}
     */
    public function acceptRecordVisitor(RecordVisitorInterface $visitor)
    {
        return $visitor->visitModifyRecord($this);
    }
}

// vim: syntax=php sw=4 ts=4 et:
