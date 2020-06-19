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

/**
 * Wrapper class for "ldap link" resource.
 *
 * The "ldap link" resource handle is returned by ldap_connect().
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapLinkInterface extends ResourceWrapperInterface
{
    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Add entries to LDAP directory
     *
     * @param  string $dn
     * @param  array  $entry
     * @param  array $serverctls
     *
     * @return bool Returns ``true`` on success or ``false`` on failure.
     *
     * @link http://php.net/manual/en/function.ldap-add.php ldap_add()
     */
    public function add(string $dn, array $entry, array $serverctls = []) : bool;

    /**
     * Bind to LDAP directory
     *
     * @param  string $bind_rdn
     * @param  string $bind_password
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-bind.php ldap_bind()
     */
    public function bind(string $bind_rdn = null, string $bind_password = null) : bool;

    /**
     * Same as ldap_close
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-close.php ldap_close()
     */
    public function close() : bool;

    /**
     * Compare value of attribute found in entry specified with DN
     *
     * @param  string $dn
     * @param  string $attribute
     * @param  string $value
     * @param  array $serverctls
     *
     * @return bool|int
     *
     * @link http://php.net/manual/en/function.ldap-compare.php ldap_compare()
     */
    public function compare(string $dn, string $attribute, string $value, array $serverctls = []);

    /**
     * Connect to an LDAP server
     *
     * @param  string|null $host_or_uri
     * @param  int $port
     *
     * @return LdapLink|bool
     *
     * @link http://php.net/manual/en/function.ldap-connect.php ldap_connect()
     */
    public static function connect(string $host_or_uri = null, int $port = 389);

    /**
     * Send LDAP pagination control
     *
     * @param  int $pagesize
     * @param  bool $iscritical
     * @param  string $cookie
     * @param  bool $iscritical
     * @param  string $cookie
     *
     * @link http://php.net/manual/en/function.ldap-control-paged-result.php ldap_control_paged_result()
     */
    public function control_paged_result(int $pagesize, bool $iscritical = false, string $cookie = "") : bool;

    /**
     * Delete an entry from a directory
     *
     * @param  string $dn
     * @param  array $serverctls
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-delete.php ldap_delete()
     */
    public function delete(string $dn, array $serverctls = []) : bool;

    /**
     * Convert DN to User Friendly Naming format
     *
     * @param  string $dn
     *
     * @return string|bool
     *
     * @link http://php.net/manual/en/function.ldap-dn2ufn.php ldap_dn2ufn()
     */
    public static function dn2ufn(string $dn);

    /**
     * Convert LDAP error number into string error message
     *
     * @param  int $errno
     *
     * @return string|bool
     *
     * @link http://php.net/manual/en/function.ldap-err2str.php ldap_err2str()
     */
    public static function err2str(int $errno);

    /**
     * Return the LDAP error number of the last LDAP command
     *
     * @return int|bool
     *
     * @link http://php.net/manual/en/function.ldap-errno.php ldap_errno()
     */
    public function errno();

    /**
     * Return the LDAP error message of the last LDAP command
     *
     * @return string|bool
     *
     * @link http://php.net/manual/en/function.ldap-error.php ldap_error()
     */
    public function error();

    /**
     * Escape a string for use in an LDAP filter or DN
     *
     * @param  string $value
     * @param  string $ignore
     * @param  int $flags
     *
     * @return string|bool
     *
     * @link http://php.net/manual/en/function.ldap-escape.php ldap_escape()
     */
    public static function escape(string $value, string $ignore = "", int $flags = 0);

    /**
     * Splits DN into its component parts
     *
     * @param  string $dn
     * @param  int with_attrib
     *
     * @return array|bool
     *
     * @link http://php.net/manual/en/function.ldap-explode-dn.php ldap_explode_dn()
     */
    public static function explode_dn($dn, $with_attrib);

//    /**
//     * Return first attribute
//     *
//     * @param ResultEntry $result_entry
//     *
//     * @return string|bool
//     *
//     * @link http://php.net/manual/en/function.ldap-first-attribute.php ldap_first_attribute()
//     */
//    public function first_attribute(ResultEntry $result_entry);

//    /**
//     * Get attributes from a search result entry
//     *
//     * @param ResultEntry $result_entry
//     *
//     * @return array|bool
//     *
//     * @link http://php.net/manual/en/function.ldap-get-attributes.php ldap_get_attributes()
//     */
//    public function get_attributes(ResultEntry $result_entry);

//    /**
//     * Get the DN of a result entry
//     *
//     * @param ResultEntry $result_record
//     *
//     * @return string|bool
//     *
//     * @link http://php.net/manual/en/function.ldap-get-dn.php ldap_get_dn()
//     */
//    public function get_dn(ResultRecord $result_record);

    /**
     * Get the current value for given option
     *
     * @param  int $option
     * @param  mixed $retval
     *
     * @return string|bool
     *
     * @link http://php.net/manual/en/function.ldap-get-option.php ldap_get_option()
     */
    public function get_option(int $option, &$retval);

//    /**
//     * Get all binary values from a result entry
//     *
//     * @param  ResultEntry $result_entry
//     * @param  string $attribute
//     *
//     * @return array|bool
//     *
//     * @link http://php.net/manual/en/function.ldap-get-values-len.php ldap_get_values_len()
//     */
//    public function get_values_len(ResultEntry $result_entry, string $attribute);

//    /**
//     * Get all values from a result entry
//     *
//     * @param  ResultEntry $result_entry
//     * @param  string $attribute
//     *
//     * @return array|bool
//     *
//     * @link http://php.net/manual/en/function.ldap-get-values.php ldap_get_values()
//     */
//    public function get_values(ResultEntry $result_entry, string $attribute);

    /**
     * Single-level search
     *
     * @param  string $base_dn
     * @param  string $filter
     * @param  array $attributes
     * @param  int $attrsonly
     * @param  int $sizelimit
     * @param  int $timelimit
     * @param  int $deref
     * @param  array $serverctrls
     *
     * @return ExtLdapResultInterface|false
     *
     * @link http://php.net/manual/en/function.ldap-list.php ldap_list()
     */
    public function list(
        string $base_dn,
        string $filter,
        array $attributes = ["*"],
        int $attrsonly = 0,
        int $sizelimit = -1,
        int $timelimit = -1,
        int $deref = LDAP_DEREF_NEVER,
        array $serverctrls = []
    );

    /**
     * Add attribute values to current attributes
     *
     * @param  string $dn
     * @param  array $entry
     * @param  array $serverctls
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-mod-add.php ldap_mod_add()
     */
    public function mod_add(string $dn, array $entry, array $serverctls = []) : bool;

    /**
     * Delete attribute values from current attributes
     *
     * @param  string $dn
     * @param  array $entry
     * @param  array $serverctls
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-mod-del.php ldap_mod_del()
     */
    public function mod_del(string $dn, array $entry, array $serverctls = []) : bool;

    /**
     * Replace attribute values with new ones
     *
     * @param  string $dn
     * @param  array $entry
     * @param  array $serverctls
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-mod-replace.php ldap_mod_replace()
     */
    public function mod_replace(string $dn, array $entry, array $serverctls = []) : bool;

    /**
     * Batch and execute modifications on an LDAP entry
     *
     * @param  string $dn
     * @param  array $entry
     * @param  array $serverctls
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-modify-batch.php ldap_modify_batch()
     */
    public function modify_batch(string $dn, array $entry, array $serverctls = []) : bool;

    /**
     * Modify an LDAP entry
     *
     * @param  string $dn
     * @param  array $entry
     * @param  array $serverctls
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-modify.php ldap_modify()
     */
    public function modify(string $dn, array $entry, array $serverctls = []) : bool;

//    /**
//     * Get the next attribute in result
//     *
//     * @param ResultEntry $result_entry
//     *
//     * @return string|bool
//     *
//     * @link http://php.net/manual/en/function.ldap-next-attribute.php ldap_next_attribute()
//     */
//    public function next_attribute(ResultEntry $result_entry);

//    /**
//     * Get next result entry
//     *
//     * @param ResultEntry $result_entry
//     *
//     * @return ResultEntry|bool
//     *
//     * @link http://php.net/manual/en/function.ldap-next-entry.php ldap_next_entry()
//     */
//    public function next_entry(ResultEntry $result_entry);

//    /**
//     * Get next reference
//     *
//     * @param ResultReference $reference
//     *
//     * @return ResultReference|bool
//     *
//     * @link http://php.net/manual/en/function.ldap-next-reference.php ldap_next_reference()
//     */
//    public function next_reference(ResultReference $reference);

//    /**
//     * Extract information from reference entry
//     *
//     * @param  ResultReference $reference
//     * @param  array|null &$referrals
//     *
//     * @return bool
//     *
//     * @link http://php.net/manual/en/function.ldap-parse-reference.php ldap_parse_reference()
//     */
//    public function parse_reference(ResultReference $reference, &$referrals) : bool;

    /**
     * Read an entry
     *
     * @param  string $base_dn
     * @param  string $filter
     * @param  array $attributes
     * @param  int $attrsonly
     * @param  int $sizelimit
     * @param  int $timelimit
     * @param  int $deref
     * @param  array $serverctrls
     *
     * @return ExtLdapResultInterface|false
     *
     * @link http://php.net/manual/en/function.ldap-list.php ldap_list()
     */
    public function read(
        string $base_dn,
        string $filter,
        array $attributes = ["*"],
        int $attrsonly = 0,
        int $sizelimit = -1,
        int $timelimit = -1,
        int $deref = LDAP_DEREF_NEVER,
        array $serverctrls = []
    );

    /**
     * Modify the name of an entry
     *
     * @param  string $dn
     * @param  string $newrdn
     * @param  string $newparent
     * @param  bool $deleteoldrdn
     * @param  array $serverctls
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-rename.php ldap_rename()
     */
    public function rename(string $dn, string $newrdn, string $newparent, bool $deleteoldrdn, array $serverctls = []);

    /**
     * Bind to LDAP directory using SASL
     *
     * @param  string|null $binddn
     * @param  string|null $password
     * @param  string|null $sasl_mech
     * @param  string|null $sasl_realm
     * @param  string|null $sasl_authc_id
     * @param  string|null $sasl_authz_id
     * @param  string|null $props
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-sasl-bind.php ldap_sasl_bind()
     */
    public function sasl_bind(
        string $binddn = null,
        string $password = null,
        string $sasl_mech = null,
        string $sasl_realm = null,
        string $sasl_authc_id = null,
        string $sasl_authz_id = null,
        string $props = null
    ) : bool;

    /**
     * Search LDAP tree
     *
     * @param  string $base_dn
     * @param  string $filter
     * @param  array $attributes
     * @param  int $attrsonly
     * @param  int $sizelimit
     * @param  int $timelimit
     * @param  int $deref
     * @param  array $serverctrls
     *
     * @return ExtLdapResultInterface|false
     *
     * @link http://php.net/manual/en/function.ldap-list.php ldap_list()
     */
    public function search(
        string $base_dn,
        string $filter,
        array $attributes = ["*"],
        int $attrsonly = 0,
        int $sizelimit = -1,
        int $timelimit = -1,
        int $deref = LDAP_DEREF_NEVER,
        array $serverctrls = []
    );

    /**
     * Set the value of the given option
     *
     * @param  int $option
     * @param  mixed $newval
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-set-option.php ldap_set_option()
     */
    public function set_option(int $option, $newval) : bool;

    /**
     * Set a callback function to do re-binds on referral chasing
     *
     * @param  callable $callback
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-set-rebind-proc.php ldap_set_rebind_proc()
     */
    public function set_rebind_proc($callback) : bool;

    /**
     * Start TLS
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-start-tls.php ldap_start_tls()
     */
    public function start_tls() : bool;

    /**
     * Unbind from LDAP directory
     *
     * @return bool
     *
     * @link http://php.net/manual/en/function.ldap-unbind.php ldap_unbind()
     */
    public function unbind() : bool;

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et:
