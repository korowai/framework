<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter\ExtLdap;

use Korowai\Lib\Ldap\Adapter\AbstractCompareQuery;
use function Korowai\Lib\Context\with;
use function Korowai\Lib\Error\emptyErrorHandler;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class CompareQuery extends AbstractCompareQuery
{
    use EnsureLdapLinkTrait;
    use LastLdapExceptionTrait;
    use LdapLinkWrapperTrait;

    /**
     * Constructs CompareQuery
     */
    public function __construct(LdapLink $link, string $dn, string $attribute, string $value)
    {
        $this->setLdapLink($link);
        parent::__construct($dn, $attribute, $value);
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecuteQuery() : bool
    {
        static::ensureLdapLink($this->getLdapLink());
        return with(emptyErrorHandler())(function ($eh) {
            // FIXME: emptyErrorHandler() is probably not a good idea, we lose
            // error information in cases the error is not an LDAP error (but,
            // for example, a type error, or resource type error).
            return $this->doExecuteQueryImpl();
        });
    }

    private function doExecuteQueryImpl() : bool
    {
        $result = $this->getLdapLink()->compare($this->dn, $this->attribute, $this->value);
        if (-1 === $result) {
            throw static::lastLdapException($this->getLdapLink());
        }
        return $result;
    }
}

// vim: syntax=php sw=4 ts=4 et:
