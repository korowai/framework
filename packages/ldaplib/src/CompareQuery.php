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
use function Korowai\Lib\Context\with;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class CompareQuery extends AbstractCompareQuery implements LdapLinkWrapperInterface
{
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
        return with(new LdapLinkErrorHandler($this->ldapLink))(function () : bool {
            if (($result = $this->ldapLink->compare($this->dn, $this->attribute, $this->value)) === -1) {
                trigger_error("LdapLink::compare() returned -1");
            }
            return $result;
        });
    }
}

// vim: syntax=php sw=4 ts=4 et:
