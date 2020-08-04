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
use Korowai\Lib\Ldap\Adapter\ResultInterface;

use function Korowai\Lib\Context\with;
use function Korowai\Lib\Error\emptyErrorHandler;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class SearchQuery extends AbstractSearchQuery
{
    use EnsureLdapLinkTrait;
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
        $this->setLdapLink($link);
        parent::__construct($base_dn, $filter, $options);
    }

    protected static function getDerefOption(array $options)
    {
        if (isset($options['deref'])) {
            return constant('LDAP_DEREF_' . strtoupper($options['deref']));
        } else {
            return LDAP_DEREF_NEVER;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecuteQuery() : ResultInterface
    {
        $options = $this->getOptions();
        $scope = strtolower(isset($options['scope']) ? $options['scope'] : 'sub');
        switch ($scope) {
            case 'base':
                $func = 'read';
                break;
            case 'one':
                $func = 'list';
                break;
            case 'sub':
                $func = 'search';
                break;
            default:
                // This should be actualy covered by OptionsResolver in AbstractSearchQuery::__construct()
                throw new \RuntimeException(sprintf('Unsupported search scope "%s"', $options['scope']));
        }

        static::ensureLdapLink($this->getLdapLink());
        return with(emptyErrorHandler())(function ($eh) use ($func) {
            // FIXME: emptyErrorHandler() is probably not a good idea, we lose
            // error information in cases the error is not an LDAP error (but,
            // for example, a type error, or resource type error).
            return $this->doExecuteQueryImpl($func);
        });
    }

    private function doExecuteQueryImpl($func)
    {
        $options = $this->getOptions();
        $result = call_user_func(
            [$this->getLdapLink(), $func],
            $this->getBaseDn(),
            $this->getFilter(),
            $options['attributes'],
            $options['attrsOnly'],
            $options['sizeLimit'],
            $options['timeLimit'],
            static::getDerefOption($options)
        );
        if (false === $result) {
            throw static::lastLdapException($this->getLdapLink());
        }
        return $result;
    }


    protected function configureOptionsResolver(OptionsResolver $resolver)
    {
        return parent::configureOptionsResolver($resolver);
    }
}

// vim: syntax=php sw=4 ts=4 et:
