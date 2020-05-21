<?php
/**
 * @file src/Records/AbstractChangeRecord.php
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
use Korowai\Lib\Ldif\Traits\DecoratesSnippetInterface;

/**
 * An abstract base class for parsed LDIF records.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractChangeRecord extends AbstractRecord
{
    /**
     * @var array
     */
    private $controls;

    /**
     * Initializes the object. Should be invoked from subclass' constructor.
     *
     * @param  SnippetInterface $snippet
     * @param  string $dn
     * @param  array $controls
     *
     * @return object $this
     */
    public function initAbstractChangeRecord(SnippetInterface $snippet, string $dn, array $controls = [])
    {
        $this->initAbstractRecord($snippet, $dn);
        $this->setControls($controls);
        return $this;
    }

    /**
     * Sets new controls to this object.
     *
     * @param  array $controls
     *
     * @return object $this
     */
    public function setControls(array $controls)
    {
        $this->controls = $controls;
        return $this;
    }

    /**
     * Returns the controls assigned to this object.
     *
     * @return array
     */
    public function getControls() : array
    {
        return $this->controls;
    }
}

// vim: syntax=php sw=4 ts=4 et:
