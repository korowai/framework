<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap;

use function Korowai\Lib\Context\with;
use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class CompareQuery implements CompareQueryInterface, LdapLinkWrapperInterface
{
    use LdapLinkWrapperTrait;

    /** @var string */
    protected $dn;
    /** @var string */
    protected $attribute;
    /** @var string */
    protected $value;
    /** @var null|bool */
    protected $result;

    /**
     * Constructs CompareQuery.
     */
    public function __construct(LdapLinkInterface $link, string $dn, string $attribute, string $value)
    {
        $this->ldapLink = $link;
        $this->dn = $dn;
        $this->attribute = $attribute;
        $this->value = $value;
    }

    /**
     * Returns ``$dn`` provided to ``__construct()`` at creation time.
     */
    public function getDn(): string
    {
        return $this->dn;
    }

    /**
     * Returns ``$attribute`` provided to ``__construct()`` at creation time.
     */
    public function getAttribute(): string
    {
        return $this->attribute;
    }

    /**
     * Returns ``$value`` provided to ``__construct()`` at creation time.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getResult(): bool
    {
        if (!isset($this->result)) {
            return $this->execute();
        }

        return $this->result;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): bool
    {
        $this->result = $this->doExecuteQuery();

        return $this->result;
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecuteQuery(): bool
    {
        return with($this->ldapLink->getErrorHandler())(function (): bool {
            if (-1 === ($result = $this->ldapLink->compare($this->dn, $this->attribute, $this->value))) {
                trigger_error('LdapLinkInterface::compare() returned -1');
            }

            return $result;
        });
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
