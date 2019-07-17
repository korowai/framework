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

//use LdapTools\Ldif\LdifParser;

/**
 * Defines application features from the specific context.
 */
class ExtLdapService
{
    const DEFAULT_URI = 'ldap://ldap-service';
    const DEFAULT_BASE = 'dc=example,dc=org';
    const DEFAULT_BINDDN = 'cn=admin,dc=example,dc=org';
    const DEFAULT_BINDPW = 'admin';
    const DEFAULT_OPTIONS = [
          LDAP_OPT_PROTOCOL_VERSION => 3
        , LDAP_OPT_SERVER_CONTROLS => [
            ['oid' => LDAP_CONTROL_MANAGEDSAIT]
          ]
    ];
    const DEFAULT_DELETE_EXCEPTIONS = [
        'cn=admin,dc=example,dc=org'
    ];


    private $ldap;
    private $base;


    public static function createInstance(string $uri=null, string $binddn=null, string $bindpw=null, array $options=null)
    {
        $ldap = self::ldapCreate($uri);
        self::ldapSetOptions($ldap, $options);
        self::ldapBind($ldap, $binddn, $bindpw);
        return new self($ldap);
    }

    protected static function ldapCreate(string $uri=null)
    {
        $ldap = ldap_connect($uri ?? self::DEFAULT_URI);
        if($ldap === FALSE) {
            throw new \RuntimeException("ldap_connect failed");
        }
        return $ldap;
    }

    protected static function ldapSetOptions($ldap, array $options=null)
    {
        if(!is_resource($ldap)) {
            $klass = self::class;
            throw new \RuntimeException("argument 1 to $klass::ldapSetOptions must be a resource");
        }
        foreach($options ?? self::DEFAULT_OPTIONS as $option => $value) {
            if(ldap_set_option($ldap, $option, $value) === false) {
                throw new \RuntimeException(ldap_error($ldap));
            }
        }
    }

    protected static function ldapBind($ldap, string $dn=null, string $pw=null)
    {
        if(ldap_bind($ldap, $dn ?? self::DEFAULT_BINDDN, $pw ?? self::DEFAULT_BINDPW) === false) {
            throw new \RuntimeException(ldap_error($ldap));
        }
    }

    protected static function ldapDeleteSubtrees($ldap, string $base=null, string $filter=null, array $except=null)
    {
        $children = @ldap_list($ldap, $base ?? self::DEFAULT_BASE, $filter ?? '(objectclass=*)', ['dn'], 0, -1, -1, LDAP_DEREF_NEVER);
        self::ldapDeleteResultEntries($ldap, $children, $filter, $except);
    }

    protected static function ldapDeleteResultEntries($ldap, $result, string $filter=null, array $except=null)
    {
        if(!$result) {
            return;
        }

        $entries = ldap_get_entries($ldap, $result);
        for($i=0; $i < $entries['count']; $i++) {
            $dn = $entries[$i]['dn'];
            self::ldapDeleteSubtrees($ldap, $dn, $filter, $except);
            if(!in_array($dn, $except ?? self::DEFAULT_DELETE_EXCEPTIONS)) {
                //@ldap_delete($ldap, $dn);
                echo("deleting: $dn\n");
            }
        }
    }

    /**
     * Initializes the ExtLdapService instance.
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

    /**
     * Delete all LDAP nodes, except the $base and dn's listed in $except.
     */
    public function deleteSubtrees(string $base=null, string $filter=null, array $except=null)
    {
        self::ldapDeleteSubtrees($this->getResource(), $base, $filter, $except);
    }


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
