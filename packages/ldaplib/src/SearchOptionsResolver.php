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

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class SearchOptionsResolver
{
    public const DEREF_OPTIONS = [
        'always' => LDAP_DEREF_ALWAYS,
        'never' => LDAP_DEREF_NEVER,
        'finding' => LDAP_DEREF_FINDING,
        'searching' => LDAP_DEREF_SEARCHING,
    ];

    /**
     * @var OptionsResolver
     */
    private $resolver;

    /**
     * Initializes the object.
     */
    public function __construct(OptionsResolver $resolver = null)
    {
        if (null === $resolver) {
            $resolver = new OptionsResolver();
        }
        $this->configureOptionsResolver($resolver);
        $this->resolver = $resolver;
    }

    /**
     * Returns defaults for query options.
     *
     * @return array Default options
     */
    public static function getDefaultOptions(): array
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
     * Returns the encapsulated OptionsResolver instance.
     */
    public function getOptionsResolver(): OptionsResolver
    {
        return $this->resolver;
    }

    /**
     * Resolves $options.
     */
    public function resolve(array $options): array
    {
        return $this->resolver->resolve($options);
    }

    /**
     * Configures OptionsResolver.
     *
     * @param OptionsResolver $resolver The resolver to be configured
     */
    private function configureOptionsResolver(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(static::getDefaultOptions());
        $this->configureAttributes($resolver);
        $this->configureAttrsOnly($resolver);
        $this->configureDeref($resolver);
        $this->configureScope($resolver);
        $this->configureSizeLimit($resolver);
        $this->configureTimeLimit($resolver);
    }

    private function configureAttributes(OptionsResolver $resolver): void
    {
        $resolver->setAllowedTypes('attributes', ['string', 'array']);
        $resolver->setNormalizer(
            'attributes',
            /** @psalm-param mixed $value */
            function (Options $options, $value): array {
                return is_array($value) ? $value : [$value];
            }
        );
    }

    private function configureAttrsOnly(OptionsResolver $resolver): void
    {
        $resolver->setAllowedTypes('attrsOnly', ['bool', 'int']);
        $resolver->setNormalizer(
            'attrsOnly',
            /** @psalm-param mixed $value */
            function (Options $options, $value): int {
                return (int) ((bool) $value);
            }
        );
    }

    private function configureDeref(OptionsResolver $resolver): void
    {
        $resolver->setAllowedValues(
            'deref',
            array_merge(
                array_keys(self::DEREF_OPTIONS),
                array_values(self::DEREF_OPTIONS)
            )
        );
        $resolver->setNormalizer(
            'deref',
            /** @psalm-param mixed $value */
            function (Options $options, $value): int {
                return is_string($value) ? self::DEREF_OPTIONS[$value] : $value;
            }
        );
    }

    private function configureScope(OptionsResolver $resolver): void
    {
        $resolver->setAllowedValues('scope', ['base', 'one', 'sub']);
    }

    private function configureSizeLimit(OptionsResolver $resolver): void
    {
        $resolver->setAllowedTypes('sizeLimit', ['int']);
    }

    private function configureTimeLimit(OptionsResolver $resolver): void
    {
        $resolver->setAllowedTypes('timeLimit', ['int']);
    }
}

// vim: syntax=php sw=4 ts=4 et:
