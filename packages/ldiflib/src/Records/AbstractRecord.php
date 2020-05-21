<?php
/**
 * @file src/Records/AbstractRecord.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Records;

use Korowai\Lib\Ldif\RecordInterface;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\Traits\HasSnippet;

/**
 * An abstract base class for parsed LDIF records.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractRecord implements RecordInterface
{
    use HasSnippet;

    /**
     * @var string
     */
    protected $dn;

    /**
     * Initializes the object. Should be invoked from subclass' constructor.
     *
     * @param  string $dn
     *
     * @return object $this
     */
    public function initAbstractRecord(string $dn, SnippetInterface $snippet = null)
    {
        $this->setDn($dn);
        $this->setSnippet($snippet);
        return $this;
    }

    /**
     * Sets new DN to this object.
     *
     * @param  string $dn
     *
     * @return object $this
     */
    public function setDn(string $dn)
    {
        $this->dn = $dn;
        return $this;
    }

    /**
     * Returns the value of DN as string.
     *
     * @return string
     */
    public function getDn() : string
    {
        return $this->dn;
    }
}

// vim: syntax=php sw=4 ts=4 et:
