<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Behat;

/**
 * Represents running LdapServce.
 */
class LdapService
{
    private static $defaultConfig = [
        'host' => 'ldap-service',
        'username' => 'cn=admin,dc=example,dc=org',
        'password' => 'admin',
        'bindRequiresDn' => true,
        'accountDomainName' => 'example.org',
        'baseDn' => 'dc=example,dc=org',
    ];

    /**
     * Store the singleton object.
     *
     * @var null|LdapService
     */
    private static $instance = null;

    /**
     * @var null|array
     */
    private $config;

    /**
     * @var \Zend\Ldap\Ldap
     */
    private $ldap;

    private function __construct(array $config = null)
    {
        $this->config = $config ?? self::$defaultConfig;
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Returns current config.
     */
    public function getConfig(): array
    {
        if (null === $this->config) {
            $this->config = self::$defaultConfig;
        }

        return $this->config;
    }

    /**
     * Returns the underlying Zend Ldap object.
     */
    public function getLdap(): \Zend\Ldap\Ldap
    {
        if (is_null($this->ldap)) {
            $ldap = new \Zend\Ldap\Ldap($this->getConfig());
            @ldap_set_option($ldap->getResource(), LDAP_OPT_SERVER_CONTROLS, [['oid' => LDAP_CONTROL_MANAGEDSAIT]]);
            $ldap->bind();
            $this->ldap = $ldap;
        }

        return $this->ldap;
    }

    /**
     * Deletes all the data, except bind dn.
     *
     * @param string $base   Base dn of the search
     * @param string $filter search filter
     *
     * @return array a list of distinguished names deleted (only roots of the deleted sub-trees are returned)
     */
    public function deleteAllData()
    {
        $config = $this->getConfig();
        $base = $config['baseDn'] ?? 'dc=example,dc=org';

        return $this->deleteDescendants($base);
    }

    /**
     * Deletes (recursively) all the direct children of $base that match the $filter.
     *
     * Only the $config['bindDn'] is preserved.
     *
     * @param string $base   Base dn of the search
     * @param string $filter search filter
     *
     * @return array a list of distinguished names deleted (only roots of the deleted sub-trees are returned)
     */
    public function deleteDescendants(string $base, string $filter = null): array
    {
        $ldap = $this->getLdap();
        $deleted = [];
        $result = $ldap->search($filter ?? '(objectclass=*)', $base, \Zend\Ldap\Ldap::SEARCH_SCOPE_ONE, ['dn']);
        if ($result) {
            foreach ($result as $entry) {
                if (null !== $entry && $this->isSafeToDeleteEntry($entry)) {
                    $ldap->delete($entry['dn'], true);
                    $deleted[] = $entry['dn'];
                }
            }
        }

        return $deleted;
    }

    /**
     * Check if the $entry can be safely deleted from database.
     */
    public function isSafeToDeleteEntry(array $entry): bool
    {
        return $this->isSafeToDeleteDn($entry['dn']);
    }

    /**
     * Check if the distinguished name $dn can be safely deleted from database.
     *
     * For example, current bindDn should not be deleted.
     */
    public function isSafeToDeleteDn(string $dn): bool
    {
        $config = $this->getConfig();

        return strtolower($dn) !== strtolower($config['username']);
    }

    /**
     * Add entries from LDIF file.
     */
    public function addFromLdifFile(string $file)
    {
        $this->addFromLdifString(file_get_contents($file));
    }

    /**
     * Add entries from LDIF string.
     */
    protected function addFromLdifString(string $ldif)
    {
        $entries = \Zend\Ldap\Ldif\Encoder::decode($ldif);
        foreach ($entries as $entry) {
            $this->getLdap()->add($entry['dn'], $entry);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
