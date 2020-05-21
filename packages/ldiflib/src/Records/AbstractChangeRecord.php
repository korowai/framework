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

use Korowai\Lib\Ldif\SnippetInterface;

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
     * @param  string $dn
     * @param  array $controls
     * @param  SnippetInterface $snippet
     *
     * @return object $this
     */
    public function initAbstractChangeRecord(string $dn, array $controls = [], SnippetInterface $snippet = null)
    {
        $this->initAbstractRecord($dn, $snippet);
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
