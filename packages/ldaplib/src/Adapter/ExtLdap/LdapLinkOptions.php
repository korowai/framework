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
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapLinkOptions
{
    /**
     * @var array
     */
    private static $ldapLinkOptionDeclarations = [
        'deref'               => ['types' => ['string', 'int'],
                                  'constant' => 'LDAP_OPT_DEREF',
                                  'values' => ['never' => LDAP_DEREF_NEVER,
                                               'searching'=> LDAP_DEREF_SEARCHING,
                                               'finding' => LDAP_DEREF_FINDING,
                                               'always' => LDAP_DEREF_ALWAYS]],
        'sizelimit'           => ['types' => 'int',    'constant' => 'LDAP_OPT_SIZELIMIT'],
        'timelimit'           => ['types' => 'int',    'constant' => 'LDAP_OPT_TIMELIMIT'],
        'network_timeout'     => ['types' => 'int',    'constant' => 'LDAP_OPT_NETWORK_TIMEOUT'],
        'protocol_version'    => ['types' => 'int',    'constant' => 'LDAP_OPT_PROTOCOL_VERSION', 'default' => 3,
                                  'values' => [2, 3]],
        'error_number'        => ['types' => 'int',    'constant' => 'LDAP_OPT_ERROR_NUMBER'],
        'referrals'           => ['types' => 'bool',   'constant' => 'LDAP_OPT_REFERRALS'],
        'restart'             => ['types' => 'bool',   'constant' => 'LDAP_OPT_RESTART'],
        'host_name'           => ['types' => 'string', 'constant' => 'LDAP_OPT_HOST_NAME'],
        'error_string'        => ['types' => 'string', 'constant' => 'LDAP_OPT_ERROR_STRING'],
        'diagnostic_message'  => ['types' => 'string', 'constant' => 'LDAP_OPT_DIAGNOSTIC_MESSAGE'],
        'matched_dn'          => ['types' => 'string', 'constant' => 'LDAP_OPT_MATCHED_DN'],
        'server_controls'     => ['types' => 'array',  'constant' => 'LDAP_OPT_SERVER_CONTROLS'],
        'client_controls'     => ['types' => 'array',  'constant' => 'LDAP_OPT_CLIENT_CONTROLS'],

        'keepalive_idle'      => ['types' => 'int',    'constant' => 'LDAP_OPT_X_KEEPALIVE_IDLE'],
        'keepalive_probes'    => ['types' => 'int',    'constant' => 'LDAP_OPT_X_KEEPALIVE_PROBES'],
        'keepalive_interval'  => ['types' => 'int',    'constant' => 'LDAP_OPT_X_KEEPALIVE_INTERVAL'],

        'sasl_mech'           => ['types' => 'string', 'constant' => 'LDAP_OPT_X_SASL_MECH'],
        'sasl_realm'          => ['types' => 'string', 'constant' => 'LDAP_OPT_X_SASL_REALM'],
        'sasl_authcid'        => ['types' => 'string', 'constant' => 'LDAP_OPT_X_SASL_AUTHCID'],
        'sasl_authzid'        => ['types' => 'string', 'constant' => 'LDAP_OPT_X_SASL_AUTHZID'],
        // PHP >= 7.1.0
        'tls_cacertdir'       => ['types' => 'string', 'constant' => 'LDAP_OPT_X_TLS_CACERTDIR'],
        'tls_cacertfile'      => ['types' => 'string', 'constant' => 'LDAP_OPT_X_TLS_CACERTFILE'],
        'tls_certfile'        => ['types' => 'string', 'constant' => 'LDAP_OPT_X_TLS_CERTFILE'],
        'tls_cipher_suite'    => ['types' => 'string', 'constant' => 'LDAP_OPT_X_TLS_CIPHER_SUITE'],
        'tls_crlcheck'        => ['types' => ['string', 'int'],
                                  'constant' => 'LDAP_OPT_X_TLS_CRLCHECK',
                                  'values' => ['none' => LDAP_OPT_X_TLS_CRL_NONE,
                                               'peer' => LDAP_OPT_X_TLS_CRL_PEER,
                                               'all' => LDAP_OPT_X_TLS_CRL_ALL]],
        'tls_crlfile'         => ['types' => 'string', 'constant' => 'LDAP_OPT_X_TLS_CRLFILE'],
        'tls_dhfile'          => ['types' => 'string', 'constant' => 'LDAP_OPT_X_TLS_DHFILE'],
        'tls_keyfile'         => ['types' => 'string', 'constant' => 'LDAP_OPT_X_TLS_KEYFILE'],
        'tls_protocol_min'    => ['types' => 'int',    'constant' => 'LDAP_OPT_X_TLS_PROTOCOL_MIN'],
        'tls_random_file'     => ['types' => 'string', 'constant' => 'LDAP_OPT_X_TLS_RANDOM_FILE'],
        // PHP >= 7.0.5
        'tls_require_cert'    => ['types' => ['string', 'int'],
                                  'constant' => 'LDAP_OPT_X_TLS_REQUIRE_CERT',
                                  'values' => ['never' => LDAP_OPT_X_TLS_NEVER,
                                               'hard' => LDAP_OPT_X_TLS_HARD,
                                               'demand' => LDAP_OPT_X_TLS_DEMAND,
                                               'allow' => LDAP_OPT_X_TLS_ALLOW,
                                               'try' => LDAP_OPT_X_TLS_TRY]],
    ];

    /**
     * Returns name of an ext-ldap option constant for a given option name
     * @return string|null Name of the ext-ldap constant
     */
    public static function getConstantName(string $optionName) : ?string
    {
        if (($constantName = self::$ldapLinkOptionDeclarations[$optionName]['constant'] ?? null) === null) {
            return null;
        };
        return defined($constantName) ? $constantName : null;
    }

    /**
     * Returns value of an ext-ldap option constant for a given option name
     *
     * @return mixed Value of the ext-ldap constant
     */
    public static function getOptionId(string $optionName)
    {
        $constantName = self::getConstantName($optionName);

        if (!$constantName) {
            // FIXME: choose another exception?
            throw new \InvalidArgumentException("Unknown option '$optionName'");
        }

        return constant($constantName);
    }

    /**
     * Returns declarations of options, mainly for use by ``configureOptionsResolver()``
     * @return array Declarations
     */
    public static function getDeclarations()
    {
        static $existingOptions;
        if (!isset($existingOptions)) {
            $existingOptions = array_filter(
                self::$ldapLinkOptionDeclarations,
                [self::class, 'getConstantName'],
                ARRAY_FILTER_USE_KEY
            );
        }
        return $existingOptions;
    }

    /**
     * Configures symfony's  OptionsResolver to parse LdapLink options
     */
    public static function configureOptionsResolver(OptionsResolver $resolver) : void
    {
        $ldapLinkOptionDeclarations = self::getDeclarations();
        foreach ($ldapLinkOptionDeclarations as $name => $decl) {
            self::configureOption($resolver, $name, $decl);
        }
    }

    /**
     * Configures symfony's OptionResolver for a single option.
     */
    public static function configureOption(OptionsResolver $resolver, string $name, array $decl) : void
    {
        if (array_key_exists('default', $decl)) {
            $resolver->setDefault($name, $decl['default']);
        } else {
            $resolver->setDefined($name);
        }
        $resolver->setAllowedTypes($name, $decl['types']);
        if (array_key_exists('values', $decl)) {
            self::setAllowedValues($resolver, $name, $decl['values']);
        }
    }

    /**
     * @param OptionsResolver $resolver
     * @param string $name
     * @param mixed $allowed
     */
    private static function setAllowedValues(OptionsResolver $resolver, string $name, $allowed) : void
    {
        if (is_array($allowed)) {
            self::setAllowedValuesArray($resolver, $name, $allowed);
        } else {
            // it can be a callback, for example...
            $resolver->setAllowedValues($name, $allowed);
        }
    }

    private static function setAllowedValuesArray(OptionsResolver $resolver, string $name, array $allowed) : void
    {
        $keys = array_keys($allowed);
        if ($keys != range(0, count($allowed)-1)) {
            // Associative array: array keys and values are the resolver's allowed values.
            $resolver->setAllowedValues($name, array_merge($keys, array_values($allowed)));
            $resolver->setNormalizer(
                $name,
                /** @psalm-param mixed $value
                 *  @psalm-return mixed */
                function (Options $options, $value) use ($allowed) {
                    return $allowed[$value] ?? $value;
                }
            );
        } else {
            $resolver->setAllowedValues($name, $allowed);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et tw=120:
