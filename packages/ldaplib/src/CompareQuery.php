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

use Korowai\Lib\Ldap\Adapter\AbstractCompareQuery;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkErrorHandler;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\EnsureLdapLinkTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LastLdapExceptionTrait;
use function Korowai\Lib\Context\with;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class CompareQuery extends AbstractCompareQuery implements LdapLinkWrapperInterface
{
    use EnsureLdapLinkTrait;
    use LastLdapExceptionTrait;
    use LdapLinkWrapperTrait;

    /**
     * Constructs CompareQuery
     *
     * @param  LdapLinkInterface $link
     * @param  string $dn
     * @param  string $attribute
     * @param  string $value
     */
    public function __construct(LdapLinkInterface $link, string $dn, string $attribute, string $value)
    {
        $this->ldapLink = $link;
        parent::__construct($dn, $attribute, $value);
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecuteQuery() : bool
    {
        static::ensureLdapLink($this->getLdapLink());
        return with(LdapLinkErrorHandler::fromLdapLinkWrapper($this))(function () {
            return $this->doExecuteQueryImpl();
        });
    }

    private function doExecuteQueryImpl() : bool
    {
        $result = $this->getLdapLink()->compare($this->dn, $this->attribute, $this->value);
        if (-1 === $result) {
            throw static::lastLdapException($this->getLdapLink());
        }
        return (bool)$result;
    }
}

// vim: syntax=php sw=4 ts=4 et:
