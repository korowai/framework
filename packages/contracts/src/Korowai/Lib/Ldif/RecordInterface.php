<?php
/**
 * @file src/Korowai/Lib/Ldif/RecordInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

/**
 * @todo Write documentation
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface RecordInterface extends SnippetInterface
{
    /**
     * Accept record visitor.
     *
     * @param  RecordVisitorInterface $visitor
     */
    public function acceptRecordVisitor(RecordVisitorInterface $visitor);
}

// vim: syntax=php sw=4 ts=4 et:
