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

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class SearchOptionsResolver
{
    public const DEREF_OPTIONS = [
        'always'    => LDAP_DEREF_ALWAYS,
        'never'     => LDAP_DEREF_NEVER,
        'finding'   => LDAP_DEREF_FINDING,
        'searching' => LDAP_DEREF_SEARCHING,
    ];

    /**
     * @var OptionsResolver
     */
    private $resolver;

    /**
     * Returns defaults for query options
     * @return array Default options
     */
    public static function getDefaultOptions() : array
    {
        return [
            'attributes' => ['*'],
            'attrsOnly' => 0,
            'deref' => LDAP_DEREF_NEVER,
            'scope' => 'sub',
            'sizeLimit' => 0,
            'timeLimit' => 0,
        ];
    }

    /**
     * Initializes the object.
     *
     * @param  OptionsResolver|null $resolver
     */
    public function __construct(OptionsResolver $resolver = null)
    {
        if ($resolver === null) {
            $resolver = new OptionsResolver;
        }
        $this->configureOptionsResolver($resolver);
        $this->resolver = $resolver;
    }

    /**
     * Returns the encapsulated OptionsResolver instance.
     *
     * @return OptionsResolver
     */
    public function getOptionsResolver() : OptionsResolver
    {
        return $this->resolver;
    }

    /**
     * Resolves $options.
     *
     * @param array $options
     * @return array
     */
    public function resolve(array $options) : array
    {
        return $this->resolver->resolve($options);
    }

    /**
     * Configures OptionsResolver.
     *
     * @param OptionsResolver $resolver The resolver to be configured
     */
    private function configureOptionsResolver(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults(static::getDefaultOptions());

        $resolver->setAllowedTypes('attributes', ['string', 'array']);
        $resolver->setAllowedTypes('attrsOnly', ['bool', 'int']);
        $resolver->setAllowedValues(
            'deref',
            array_merge(
                array_keys(self::DEREF_OPTIONS),
                array_values(self::DEREF_OPTIONS)
            )
        );
        $resolver->setAllowedValues('scope', ['base', 'one', 'sub']);
        $resolver->setAllowedTypes('sizeLimit', ['int']);
        $resolver->setAllowedTypes('timeLimit', ['int']);

        $resolver->setNormalizer(
            'attributes',
            /** @psalm-param mixed $value */
            function (Options $options, $value) : array {
                return is_array($value) ? $value : [$value];
            }
        );

        $resolver->setNormalizer(
            'deref',
            /** @psalm-param mixed $value */
            function (Options $options, $value) : int {
                return is_string($value) ? self::DEREF_OPTIONS[$value] : $value;
            }
        );

        $resolver->setNormalizer(
            'attrsOnly',
            /** @psalm-param mixed $value */
            function (Options $options, $value) : int {
                return (int)((bool)$value);
            }
        );
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
