<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldap
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldap\Behat;

//use Korowai\Component\Ldap\Behat\LdifParser;

/**
 * Manages LDAP database used by Behat tests.
 */
class ExtLdapManager
{
//    const DEFAULT_CONFIG = [
//        'connect' => ['ldap://ldap-service'],
//        'bind' => ['cn=admin,dc=example,dc=org', 'admin'],
//        'base' => 'dc=example,dc=org',
//        'options' => [
//            [LDAP_OPT_PROTOCOL_VERSION, 3],
//            [LDAP_OPT_SERVER_CONTROLS, [['oid' => LDAP_CONTROL_MANAGEDSAIT]]]
//        ],
//        'precious' => [
//            'cn=admin,dc=example,dc=org'
//        ]
//    ];
//
//
    private $ldap;

    public static function createInstance(array $config)
    {
        $ldap = self::ldapCreate($config);
        return new self($ldap);
    }

    public static function ldapCreate(array $config)
    {
        $ldap = self::ldapConnect(...$config['connect']);
        self::ldapSetOptions($ldap, $config['options']);
        self::ldapBind($ldap, ...$config['bind']);
        return $ldap;
    }

    protected static function ldapConnect(...$args)
    {
        $ldap = ldap_connect(...$args);
        if($ldap === FALSE) {
            throw new \RuntimeException("ldap_connect() failed");
        }
        return $ldap;
    }

    protected static function ldapSetOptions($ldap, array $options)
    {
        if(!is_resource($ldap)) {
            $klass = self::class;
            $msg = "argument 1 to $klass::ldapSetOptions must be a resource";
            throw new \RuntimeException($msg);
        }
        foreach($options as $args) {
            if(ldap_set_option($ldap, ...$args) === false) {
                throw new \RuntimeException(ldap_error($ldap));
            }
        }
    }

    protected static function ldapBind($ldap, ...$args)
    {
        if(ldap_bind($ldap, ...$args) === false) {
            throw new \RuntimeException(ldap_error($ldap));
        }
    }

    public static function ldapDeleteSubtrees($ldap, string $dn, array $except=null, string $filter=null)
    {
        $f = $filter ?? 'objectclass=*';
        $args = [$dn, $f, ['dn'], 0, -1, -1, LDAP_DEREF_NEVER];
        if(PHP_VERSION_ID >= 70300) {
            $args[] = [ ['oid' => LDAP_CONTROL_MANAGEDSAIT] ];
        }
        $result = @ldap_list($ldap, ...$args);
        if($result === false) {
            throw new \RuntimeException(ldap_error($ldap));
        }
        $entries = ldap_get_entries($ldap, $result);
        $dns = array_unique(self::extractDns($entries));

        $clean = true;
        foreach($dns as $dn) {
            $tree = self::ldapDeleteTree($ldap, $dn, $except, $filter);
            $clean = $clean && $tree;
        }
        return $clean;
    }

    public static function ldapDeleteTree($ldap, string $dn, array $except=null, string $filter=null)
    {
        if(!in_array($dn, $except ?? [])) {
            $clean = self::ldapDeleteSubtrees($ldap, $dn, $except, $filter);
            if($clean) {
                //@ldap_delete($ldap, $dn);
                echo("deleting: $dn\n");
            }
            return $clean;
        }
        return false;
    }

    public static function extractDns(array $entries)
    {
        $fcn = function(array $entry) { return $entry['dn']; };
        return array_map($fcn, self::extractEntries($entries));
    }

    public static function extractEntries(array $entries)
    {
        $fcn = function(int $i) use ($entries) { return $entries[$i]; };
        return array_map($fcn, self::indexSequence(0, $entries['count']));
    }

    public static function indexSequence(int $start, int $count)
    {
        $sequence = range($start, $start + $count);
        array_pop($sequence);
        return $sequence;
    }

    /**
     * Initializes the ExtLdapManager instance.
     */
    public function __construct($ldap)
    {
        if(!is_resource($ldap)) {
            $klass = self::class;
            throw new \RuntimeException("argument 1 to $klass::__construct() must be a resource");
        }
        $this->ldap = $ldap;
    }

    /**
     * Returns the PHP resource representing LDAP connection used by this
     * instance.
     */
    public function getResource()
    {
        return $this->ldap;
    }

//    /**
//     * Delete all LDAP nodes, except the $base and dn's listed in $except.
//     */
//    public function deleteSubtrees(string $base, string $filter, array $except=null)
//    {
//        self::ldapDeleteSubtrees($this->getResource(), $base, $filter, $except);
//    }
//
//
//    protected function reinitializeLdapDatabase()
//    {
//        $ldap = $this->ldapBindService();
//        $this->deleteLdapData($ldap);
//        $this->initializeLdapData($ldap);
//        ldap_close($ldap);
//    }
//
//    protected function ldapBindService()
//    {
//        $ldap = ldap_connect('ldap://ldap-service');
//        if($ldap === FALSE) {
//            throw new \RuntimeException("ldap_connect failed");
//        }
//
//        $status = ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
//        if($status === FALSE) {
//            throw new \RuntimeException(ldap_error($ldap));
//        }
//        $status = ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
//        if($status === FALSE) {
//            throw new \RuntimeException(ldap_error($ldap));
//        }
//
//        $status = ldap_bind($ldap, 'cn=admin,dc=example,dc=org', 'admin');
//        if($status === FALSE) {
//            throw new \RuntimeException(ldap_error($ldap));
//        }
//
//        return $ldap;
//    }
//
//    protected function deleteLdapData($ldap)
//    {
//        $this->deleteLdapDescendants($ldap, 'dc=example,dc=org', '(&(objectclass=*)(!(cn=admin)))');
//    }
//
//    protected function deleteLdapDescendants($ldap, $base, $filter=null)
//    {
//        $children = @ldap_list($ldap, $base, $filter ?? '(objectclass=*)', ['dn'], 0, -1, -1, LDAP_DEREF_NEVER);
//        //var_dump(ldap_get_entries($ldap, $children));
//        $this->deleteLdapResult($ldap, $children);
//    }
//
//    protected function deleteLdapResult($ldap, $result)
//    {
//        if(!$result) {
//            return;
//        }
//
//        $reference = @ldap_first_reference($ldap, $result);
//        while($reference) {
//            if(ldap_parse_reference($ldap, $reference, $referrals)) {
//                var_dump($referrals);
//                //var_dump(ldap_get_attributes($ldap, $reference));
//                //var_dump(ldap_get_dn($ldap, $reference));
//            }
//            $reference = ldap_next_reference($ldap, $reference);
//        }
//
//        $entries = ldap_get_entries($ldap, $result);
//        for($i=0; $i < $entries['count']; $i++) {
//            $dn = $entries[$i]['dn'];
//            $this->deleteLdapDescendants($ldap, $dn);
//            //ldap_delete($ldap, $dn);
//        }
//    }
//
//    protected function initializeLdapData($ldap)
//    {
//        $parser = new LdifParser();
//        $ldif = file_get_contents(__DIR__ . '/../Resources/ldif/bootstrap.ldif');
//        $ldif = $parser->parse($ldif);
//        foreach($ldif->toOperations() as $op) {
//            $func = $op->getLdapFunction();
//            $args = $op->getArguments();
//            call_user_func($func, $ldap, ...$args);
//        }
//    }
}

// vim: syntax=php sw=4 ts=4 et:
