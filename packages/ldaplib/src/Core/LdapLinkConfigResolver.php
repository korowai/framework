<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Core;

use function Korowai\Lib\Compat\preg_match;
use Korowai\Lib\Rfc\Rfc3986;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Resolves configuration options for LdapLinkFactory.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapLinkConfigResolver implements LdapLinkConfigResolverInterface
{
    /**
     * @var OptionsResolver
     */
    private $resolver;

    /**
     * @var LdapLinkOptionsSpecificationInterface
     *
     * @psalm-readonly
     */
    private $optionsSpecificaton;

    /**
     * Initializes the object.
     */
    public function __construct(LdapLinkOptionsSpecificationInterface $optionsSpecificaton)
    {
        $this->configureOptionsResolver($resolver = new OptionsResolver(), $optionsSpecificaton);
        $this->resolver = $resolver;
        $this->optionsSpecificaton = $optionsSpecificaton;
    }

    /**
     * Returns the encapsulated OptionsResolver instance.
     *
     * @psalm-mutation-free
     */
    public function getOptionsResolver(): OptionsResolver
    {
        return $this->resolver;
    }

    /**
     * Returns the encapsulated LdapLinkOptionsSpecificationInterface.
     *
     * @psalm-mutation-free
     */
    public function getOptionsSpecification(): LdapLinkOptionsSpecificationInterface
    {
        return $this->optionsSpecificaton;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(array $config): array
    {
        $resolved = $this->resolver->resolve($config);
        if (null !== ($options = $resolved['options'] ?? null)) {
            $resolved['options'] = $this->optionsSpecificaton->getOptionsMapper()->mapOptions($options);
        }

        return $resolved;
    }

    /**
     * Configures OptionsResolver.
     *
     * @param OptionsResolver                       $resolver            The resolver to be configured
     * @param LdapLinkOptionsSpecificationInterface $optionsSpecificaton Specification of the nested options
     */
    private function configureOptionsResolver(
        OptionsResolver $resolver,
        LdapLinkOptionsSpecificationInterface $optionsSpecificaton
    ): void {
        $this->configureTopLevelOptionsResolver($resolver);
        $resolver->setDefault('options', function (OptionsResolver $nestedResolver) use ($optionsSpecificaton): void {
            $optionsSpecificaton->configureOptionsResolver($nestedResolver);
        });
    }

    /**
     * Configures OptionsResolver for top-level options.
     *
     * @param OptionsResolver $resolver The resolver to be configured
     */
    private function configureTopLevelOptionsResolver(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'uri' => 'ldap://localhost',
            'tls' => false,
        ]);
        $resolver->setAllowedTypes('uri', 'string');
        $resolver->setAllowedTypes('tls', 'bool');

        $resolver->setAllowedValues('uri', function (string $uri): bool {
            if (!preg_match('/^'.Rfc3986::URI.'$/', $uri, $matches, PREG_UNMATCHED_AS_NULL)) {
                return false;
            }
            if (($matches['host'] ?? null) === null) {
                return false;
            }
            if (null !== ($port = $matches['port'] ?? null)) {
                $port = intval($port);
                if ($port <= 0 || $port > 65535) {
                    return false;
                }
            }

            return true;
        });
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
