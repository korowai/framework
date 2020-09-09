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

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkErrorHandler;
use Korowai\Lib\Ldap\Adapter\ExtLdap\Result;

use function Korowai\Lib\Context\with;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class SearchQuery implements SearchQueryInterface, LdapLinkWrapperInterface
{
    use LdapLinkWrapperTrait;

    public const SCOPES_METHODS = [
        'base' => 'read',
        'one'  => 'list',
        'sub'  => 'search',
    ];

    /** @var string */
    protected $base_dn;
    /** @var string */
    protected $filter;
    /** @var ResultInterface|null */
    protected $result;
    /** @var array */
    protected $options;

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
        $this->base_dn = $base_dn;
        $this->filter = $filter;
        // FIXME: use dependency injection?
        $resolver = new SearchOptionsResolver;
        $this->options = $resolver->resolve($options);
    }

    /**
     * Returns ``$base_dn`` provided to ``__construct()``
     * @return string The ``$base_dn`` value provided to ``__construct()``
     */
    public function getBaseDn()
    {
        return $this->base_dn;
    }

    /**
     * Returns ``$filter`` provided to ``__construct()``
     * @return string The ``$filter`` value provided to ``__construct()``
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Get the options used by this query.
     *
     * The returned array contains ``$options`` provided to ``__construct()``,
     * but also includes defaults applied internally by this object.
     *
     * @return array Options used by this query
     */
    public function getOptions() : array
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function execute() : ResultInterface
    {
        $options = $this->getOptions();
        $method = static::selectSearchMethod($options);
        $this->result = $this->invokeQueryMethod($method, $options);
        return $this->result;
    }

    /**
     * {@inheritdoc}
     */
    public function getResult() : ResultInterface
    {
        if (!isset($this->result)) {
            return $this->execute();
        }
        return $this->result;
    }

    private function invokeQueryMethod(string $method, array $options) : ResultInterface
    {
        $link = $this->getLdapLink();
        $ldapResult = with(new LdapLinkErrorHandler($link))(
            /** @psalm-return LdapResultInterface|false */
            function () use ($link, $method, $options) {
                $result = call_user_func(
                    [$link, $method],
                    $this->getBaseDn(),
                    $this->getFilter(),
                    $options['attributes'],
                    $options['attrsOnly'],
                    $options['sizeLimit'],
                    $options['timeLimit'],
                    $options['deref']
                );
                if ($result === false) {
                    trigger_error('LdapLinkInterface::'.$method.'() returned false');
                }
                return $result;
            }
        );
        return new Result($ldapResult);
    }

    private static function selectSearchMethod(array $options) : string
    {
        $scope = strtolower($options['scope'] ?? 'sub');
        return self::SCOPES_METHODS[$scope] ?? 'search';
    }
}

// vim: syntax=php sw=4 ts=4 et:
