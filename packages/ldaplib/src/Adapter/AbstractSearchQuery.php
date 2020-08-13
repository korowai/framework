<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractSearchQuery implements SearchQueryInterface
{
    /** @var string */
    protected $base_dn;
    /** @var string */
    protected $filter;
    /** @var ResultInterface|null */
    protected $result;
    /** @var array */
    protected $options;


    /**
     * Constructs AbstractSearchQuery
     *
     * @param  string $base_dn
     * @param  string $filter
     * @param  array $options
     */
    public function __construct(string $base_dn, string $filter, array $options = [])
    {
        $this->base_dn = $base_dn;
        $this->filter = $filter;
        $resolver = new OptionsResolver;
        $this->configureOptionsResolver($resolver);
        $this->options = $resolver->resolve($options);
    }

    /**
     * Returns defaults for query options
     * @return array Default options
     */
    public static function getDefaultOptions() : array
    {
        return [
            'scope' => 'sub',
            'attributes' => '*',
            'attrsOnly' => 0,
            'deref' => 'never',
            'sizeLimit' => 0,
            'timeLimit' => 0,
        ];
    }

    /**
     * Returns ``$base_dn`` provided to ``__construct()`` at creation time
     * @return string The ``$base_dn`` value provided to ``__construct()``
     */
    public function getBaseDn()
    {
        return $this->base_dn;
    }

    /**
     * Returns ``$filter`` provided to ``__construct()`` at creation time
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
    public function getResult() : ResultInterface
    {
        if (!isset($this->result)) {
            return $this->execute();
        }
        return $this->result;
    }

    /**
     * {@inheritdoc}
     */
    public function execute() : ResultInterface
    {
        $this->result = $this->doExecuteQuery();
        return $this->result;
    }

    /**
     * Executes query and returns result
     *
     * This method should be implemented in subclass.
     *
     * @return ResultInterface Result of the query.
     */
    abstract protected function doExecuteQuery() : ResultInterface;


    /**
     * @internal
     */
    protected function configureOptionsResolver(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults(static::getDefaultOptions());

        $resolver->setAllowedValues('scope', ['base', 'one', 'sub']);
        $resolver->setAllowedValues('deref', ['always', 'never', 'finding', 'searching']);

        $resolver->setNormalizer(
            'attributes',
            /** @psalm-param mixed $value */
            function (Options $optins, $value) : array {
                return is_array($value) ? $value : [$value];
            }
        );
    }
}

// vim: syntax=php sw=4 ts=4 et:
