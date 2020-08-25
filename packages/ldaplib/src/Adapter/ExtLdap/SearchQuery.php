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

use Korowai\Lib\Ldap\Adapter\AbstractSearchQuery;
use Korowai\Lib\Ldap\ResultInterface;

use function Korowai\Lib\Context\with;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class SearchQuery extends AbstractSearchQuery implements LdapLinkWrapperInterface
{
    use LastLdapExceptionTrait;
    use LdapLinkWrapperTrait;

    /**
     * Constructs SearchQuery
     *
     * @param  LdapLinkInterface $link
     * @param  string $base_dn
     * @param  string $filter
     * @param  array $options
     */
    public function __construct(LdapLinkInterface $link, string $base_dn, string $filter, array $options = [])
    {
        $this->ldapLink = $link;
        parent::__construct($base_dn, $filter, $options);
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecuteQuery() : ResultInterface
    {
        $options = $this->getOptions();
        $method = static::selectSearchMethod($options);
        return $this->invokeQueryMethod($method, $options);
    }

    private function invokeQueryMethod(string $method, array $options) : ResultInterface
    {
        $link = $this->getLdapLink();
        $ldapResult = with(new LdapLinkErrorHandler($link))(
            /** @psalm-return LdapResultInterface|false */
            function () use ($link, $method, $options) {
                return call_user_func(
                    [$link, $method],
                    $this->getBaseDn(),
                    $this->getFilter(),
                    $options['attributes'],
                    $options['attrsOnly'],
                    $options['sizeLimit'],
                    $options['timeLimit'],
                    static::getDerefOption($options)
                );
            }
        );
        if (false === $ldapResult) {
            throw static::lastLdapException($link);
        }
        return new Result($ldapResult);
    }

    private static function getDerefOption(array $options) : int
    {
        if (isset($options['deref'])) {
            return constant('LDAP_DEREF_' . strtoupper($options['deref']));
        } else {
            return LDAP_DEREF_NEVER;
        }
    }

    private static function selectSearchMethod(array $options) : string
    {
        $scope = strtolower($options['scope'] ?? 'sub');
        switch ($scope) {
            case 'base':
                return 'read';
            case 'one':
                return 'list';
            case 'sub':
                return 'search';
            default:
                // This should be actualy covered by OptionsResolver in AbstractSearchQuery::__construct()
                throw new \RuntimeException(sprintf('Unsupported search scope "%s"', $options['scope']));
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
