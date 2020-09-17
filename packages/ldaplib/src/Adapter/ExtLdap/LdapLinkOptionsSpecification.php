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

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Configures symfony's OptionsResolver for nested LdapLink options.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapLinkOptionsSpecification implements LdapLinkOptionsSpecificationInterface
{
    /**
     * @var array
     */
    public const OPTIONS = [
        'deref'               => [
            'types'           => ['string', 'int'],
            'cvalues'         => [
                'never'       => 'LDAP_DEREF_NEVER',
                'searching'   => 'LDAP_DEREF_SEARCHING',
                'finding'     => 'LDAP_DEREF_FINDING',
                'always'      => 'LDAP_DEREF_ALWAYS',
            ]
        ],
        'sizelimit'           => ['types' => 'int'],
        'timelimit'           => ['types' => 'int'],
        'network_timeout'     => ['types' => 'int'],
        'timeout'             => ['types' => 'int'],
        'protocol_version'    => [
            'types' => 'int',
            'default' => 3,
            'values' => [2, 3]
        ],
        'error_number'        => ['types' => 'int'],
        'referrals'           => ['types' => 'bool'],
        'restart'             => ['types' => 'bool'],
        'host_name'           => ['types' => 'string'],
        'error_string'        => ['types' => 'string'],
        'matched_dn'          => ['types' => 'string'],
        'server_controls'     => ['types' => 'array'],
        'client_controls'     => ['types' => 'array'],
        'debug_level'         => ['types' => 'int'],
        'diagnostic_message'  => ['types' => 'string'],
        'sasl_mech'           => ['types' => 'string'],
        'sasl_realm'          => ['types' => 'string'],
        'sasl_authcid'        => ['types' => 'string'],
        'sasl_authzid'        => ['types' => 'string'],
        'sasl_nocanon'        => ['types' => 'bool'],
        'sasl_username'       => ['types' => 'string'],
        'tls_require_cert'    => [
            'types'           => ['string', 'int'],
            'cvalues'         => [
                'never'       => 'LDAP_OPT_X_TLS_NEVER',
                'hard'        => 'LDAP_OPT_X_TLS_HARD',
                'demand'      => 'LDAP_OPT_X_TLS_DEMAND',
                'allow'       => 'LDAP_OPT_X_TLS_ALLOW',
                'try'         => 'LDAP_OPT_X_TLS_TRY',
            ]
        ],
        'tls_cacertdir'       => ['types' => 'string'],
        'tls_cacertfile'      => ['types' => 'string'],
        'tls_certfile'        => ['types' => 'string'],
        'tls_cipher_suite'    => ['types' => 'string'],
        'tls_keyfile'         => ['types' => 'string'],
        'tls_random_file'     => ['types' => 'string'],
        'tls_crlcheck'        => [
            'types'           => ['string', 'int'],
            'cvalues'         => [
                'none'        => 'LDAP_OPT_X_TLS_CRL_NONE',
                'peer'        => 'LDAP_OPT_X_TLS_CRL_PEER',
                'all'         => 'LDAP_OPT_X_TLS_CRL_ALL',
            ]
        ],
        'tls_dhfile'          => ['types' => 'string'],
        'tls_crlfile'         => ['types' => 'string'],
        'tls_protocol_min'    => [
            'types' => ['string', 'int'],
            'validator'  => [self::class, 'checkTlsProtocolMin'],
            'normalizer' => [self::class, 'normalizeTlsProtocolMin'],
            'predefined' => [
                'ssl2'        => 'LDAP_OPT_X_TLS_PROTOCOL_SSL2',
                'ssl3'        => 'LDAP_OPT_X_TLS_PROTOCOL_SSL3',
                'tls1.0'      => 'LDAP_OPT_X_TLS_PROTOCOL_TLS1_0',
                'tls1.1'      => 'LDAP_OPT_X_TLS_PROTOCOL_TLS1_1',
                'tls1.2'      => 'LDAP_OPT_X_TLS_PROTOCOL_TLS1_2',
            ],
        ],
        'tls_package'         => ['types' => 'string'],
        'keepalive_idle'      => ['types' => 'int'],
        'keepalive_probes'    => ['types' => 'int'],
        'keepalive_interval'  => ['types' => 'int'],
    ];

    /**
     * @var LdapLinkOptionsMapperInterface
     *
     * @psalm-readonly
     */
    private $mapper;

    /**
     * @var array
     *
     * @psalm-readonly
     */
    private $options;

    /**
     * Initializes the object
     *
     * @param LdapLinkOptionsMapperInterface $mapper
     */
    public function __construct(LdapLinkOptionsMapperInterface $mapper)
    {
        $this->mapper = $mapper;
        $this->options = self::getSupportedOptions($mapper);
    }

    /**
     * Returns the encapsulated LdapLinkOptionsMapperInterface
     *
     * @return LdapLinkOptionsMapperInterface
     *
     * @psalm-mutation-free
     */
    public function getOptionsMapper() : LdapLinkOptionsMapperInterface
    {
        return $this->mapper;
    }

    /**
     * Returns declarations used by this object to configure OptionsResolver.
     *
     * @return array
     *
     * @psalm-mutation-free
     */
    public function getOptions() : array
    {
        return $this->options;
    }

    /**
     * Configures symfony's  OptionsResolver to parse LdapLink options
     */
    public function configureOptionsResolver(OptionsResolver $resolver) : void
    {
        foreach ($this->options as $name => $spec) {
            self::configureOption($resolver, $name, $spec);
        }
    }

    /**
     * Configures symfony's OptionResolver for a single option.
     *
     * @param OptionsResolver $resolver
     * @param string $name
     * @param array $spec
     */
    private static function configureOption(OptionsResolver $resolver, string $name, array $spec) : void
    {
        if (array_key_exists('default', $spec)) {
            $resolver->setDefault($name, $spec['default']);
        } else {
            $resolver->setDefined($name);
        }

        $resolver->setAllowedTypes($name, $spec['types']);

        self::configureAllowedValues($resolver, $name, $spec);

        self::configureNormalizer($resolver, $name, $spec);
    }

    private static function configureAllowedValues(OptionsResolver $resolver, string $name, array $spec) : void
    {
        if (array_key_exists('values', $spec)) {
            self::addAllowedValues($resolver, $name, $spec['values'], false);
        }

        if (array_key_exists('cvalues', $spec)) {
            self::addAllowedValues($resolver, $name, $spec['cvalues'], true);
        }

        if (array_key_exists('validator', $spec)) {
            self::addAllowedValues(
                $resolver,
                $name,
                /**
                 * @psalm-param mixed $value
                 */
                function ($value) use ($spec) : bool {
                    return call_user_func($spec['validator'], $value);
                },
                false
            );
        }
    }

    private static function configureNormalizer(OptionsResolver $resolver, string $name, array $spec) : void
    {
        if (array_key_exists('normalizer', $spec)) {
            $resolver->setNormalizer(
                $name,
                /**
                 * @psalm-param mixed $value
                 * @psalm-return mixed
                 */
                function (Options $options, $value) use ($spec) {
                    return call_user_func($spec['normalizer'], $options, $value);
                }
            );
        }
    }

    /**
     * @param OptionsResolver $resolver
     * @param string $name
     * @param mixed $allowed
     * @param bool $isCvalue
     */
    private static function addAllowedValues(OptionsResolver $resolver, string $name, $allowed, bool $isCvalue) : void
    {
        if (is_array($allowed)) {
            self::addAllowedValuesArray($resolver, $name, $isCvalue ? array_map('constant', $allowed) : $allowed);
        } else {
            // it can be a callback, for example...
            $resolver->addAllowedValues($name, $isCvalue ? constant($allowed) : $allowed);
        }
    }

    /**
     * @param OptionsResolver $resolver
     * @param string $name
     * @param array $allowed
     */
    private static function addAllowedValuesArray(OptionsResolver $resolver, string $name, array $allowed) : void
    {
        $keys = array_keys($allowed);
        if ($keys != range(0, count($allowed)-1)) {
            // Associative array: array keys and values are the resolver's allowed values.
            $resolver->addAllowedValues($name, array_merge($keys, array_values($allowed)));
            $resolver->setNormalizer(
                $name,
                /** @psalm-param mixed $value
                 *  @psalm-return mixed */
                function (Options $options, $value) use ($allowed) {
                    return $allowed[$value] ?? $value;
                }
            );
        } else {
            $resolver->addAllowedValues($name, $allowed);
        }
    }

    /**
     * Returns declarations for supported options (the supported option names are determined by the $mapper).
     *
     * @param LdapLinkOptionsMapperInterface $mapper
     * @return array
     */
    private static function getSupportedOptions(LdapLinkOptionsMapperInterface $mapper) : array
    {
        return array_intersect_key(self::OPTIONS, $mapper->getMappings());
    }

    /**
     * Validates the tls_protocol_min option.
     *
     * @param mixed $value
     * @return bool
     */
    public static function checkTlsProtocolMin($value) : bool
    {
        $versions = self::OPTIONS['tls_protocol_min']['predefined'];
        return is_int($value) || array_key_exists($value, $versions);
    }

    /**
     * Normalizes value of tls_protocol_min option.
     *
     * @param Options $options
     * @param mixed $value
     */
    public static function normalizeTlsProtocolMin(Options $options, $value) : int
    {
        $versions = self::OPTIONS['tls_protocol_min']['predefined'];
        return is_int($value) ? $value : constant($versions[$value]);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
