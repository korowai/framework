<?php
/**
 * @file src/Records/ModDnRecord.php
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
 * *ldif-change-record* of type *change-moddn*.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ModDnRecord extends AbstractChangeRecord implements ModDnRecordInterface
{
    /**
     * @var string
     */
    private $changeType;

    /**
     * @var string
     */
    private $newRdn;

    /**
     * @var bool
     */
    private $deleteOldRdn;

    /**
     * @var string
     */
    private $newSuperior;

    /**
     * Initializes the object.
     *
     * Supported *$options* (case insensitive):
     *
     * - ``changetype`` (string) - one of ``"moddn"`` or ``"modrdn"``, defaults to ``"modrdn"``,
     * - ``deleteoldrdn`` (bool) - whether to delete old RDN or not, defaults to ``false``,
     * - ``newsuperior`` (string|null) - new superior DN, defaults to ``null``.
     *
     * @param  string $dn
     * @param  string $newRdn
     * @param  array $options
     * @throws InvalidChangeTypeException
     */
    public function __construct(string $dn, string $newRdn, array $options = [])
    {
        parent::initAbstractChangeRecord($dn, $options['controls'] ?? [], $options['snippet'] ?? null);
        $this->setNewRdn($newRdn);

        $options = array_change_key_case($options, CASE_LOWER);

        $this->setChangeType($options['changetype'] ?? 'modrdn');
        $this->setDeleteOldRdn($options['deleteoldrdn'] ?? false);
        $this->setNewSuperior($options['newsuperior'] ?? null);
    }

    /**
     * Sets the change type.
     *
     * @param  string $changeType Must be one of ``"moddn"`` or ``"modrdn"``.
     * @return object $this
     * @throws InvalidChangeTypeException
     */
    public function setChangeType(string $changeType)
    {
        if (!in_array(strtolower($changeType), ["moddn", "modrdn"])) {
            $message = 'Argument 1 to '.__class__.'::setChangeType() must be one of "moddn" or "modrdn", "'.
                       $changeType.'" given.';
            throw new InvalidChangeTypeException($message);
        }
        $this->changeType = strtolower($changeType);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getChangeType() : string
    {
        return $this->changeType;
    }

    /**
     * Sets the new RDN.
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
     * Sets the boolean flag determining whether to delete old RDN or not.
     *
     * @param  bool $deleteOldRdn
     * @return object $this
     */
    public function setDeleteOldRdn(bool $deleteOldRdn)
    {
        $this->deleteOldRdn = $deleteOldRdn;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDeleteOldRdn() : bool
    {
        return $this->deleteOldRdn;
    }

    /**
     * Sets the distinguished name of new superior.
     *
     * @param  bool $newSuperior
     * @return object $this
     */
    public function setNewSuperior(?string $newSuperior)
    {
        $this->newSuperior = $newSuperior;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewSuperior() : ?string
    {
        return $this->newSuperior;
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
