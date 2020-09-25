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

use Korowai\Lib\Basic\ResourceWrapperInterface;
use Korowai\Lib\Error\ErrorHandlerInterface;

/**
 * Wrapper class for "ldap link" resource.
 *
 * The "ldap link" resource handle is returned by ldap_connect().
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * @psalm-immutable
 */
interface LdapLinkInterface extends ResourceWrapperInterface
{
    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Add entries to LDAP directory.
     *
     * @return bool returns ``true`` on success or ``false`` on failure
     *
     * @see http://php.net/manual/en/function.ldap-add.php ldap_add()
     */
    public function add(string $dn, array $entry, array $serverctls = []): bool;

    /**
     * Bind to LDAP directory.
     *
     * @see http://php.net/manual/en/function.ldap-bind.php ldap_bind()
     */
    public function bind(string $bind_rdn = '', string $bind_password = ''): bool;

    /**
     * Same as ldap_close.
     *
     * @see http://php.net/manual/en/function.ldap-close.php ldap_close()
     */
    public function close(): bool;

    /**
     * Compare value of attribute found in entry specified with DN.
     *
     * @return bool|int
     *
     * @see http://php.net/manual/en/function.ldap-compare.php ldap_compare()
     */
    public function compare(string $dn, string $attribute, string $value, array $serverctls = []);

    /**
     * Connect to an LDAP server.
     *
     * @return false|LdapLink
     *
     * @see http://php.net/manual/en/function.ldap-connect.php ldap_connect()
     */
    public static function connect(string $host_or_uri = null, int $port = 389);

    /**
     * Delete an entry from a directory.
     *
     * @see http://php.net/manual/en/function.ldap-delete.php ldap_delete()
     */
    public function delete(string $dn, array $serverctls = []): bool;

    /**
     * Convert DN to User Friendly Naming format.
     *
     * @return false|string
     *
     * @see http://php.net/manual/en/function.ldap-dn2ufn.php ldap_dn2ufn()
     */
    public static function dn2ufn(string $dn);

    /**
     * Convert LDAP error number into string error message.
     *
     * @return false|string
     *
     * @see http://php.net/manual/en/function.ldap-err2str.php ldap_err2str()
     *
     * @psalm-mutation-free
     */
    public static function err2str(int $errno);

    /**
     * Return the LDAP error number of the last LDAP command.
     *
     * @return false|int
     *
     * @see http://php.net/manual/en/function.ldap-errno.php ldap_errno()
     */
    public function errno();

    /**
     * Return the LDAP error message of the last LDAP command.
     *
     * @return false|string
     *
     * @see http://php.net/manual/en/function.ldap-error.php ldap_error()
     */
    public function error();

    /**
     * Escape a string for use in an LDAP filter or DN.
     *
     * @return false|string
     *
     * @see http://php.net/manual/en/function.ldap-escape.php ldap_escape()
     */
    public static function escape(string $value, string $ignore = '', int $flags = 0);

    /**
     * Splits DN into its component parts.
     *
     * @param  int with_attrib
     *
     * @return array|false
     *
     * @see http://php.net/manual/en/function.ldap-explode-dn.php ldap_explode_dn()
     */
    public static function explode_dn(string $dn, int $with_attrib);

    /**
     * Get the current value for given option.
     *
     * @param mixed $retval
     *
     * @see http://php.net/manual/en/function.ldap-get-option.php ldap_get_option()
     */
    public function get_option(int $option, &$retval): bool;

    /**
     * Single-level search.
     *
     * @return false|LdapResultInterface
     *
     * @see http://php.net/manual/en/function.ldap-list.php ldap_list()
     */
    public function list(
        string $base_dn,
        string $filter,
        array $attributes = ['*'],
        int $attrsonly = 0,
        int $sizelimit = -1,
        int $timelimit = -1,
        int $deref = LDAP_DEREF_NEVER,
        array $serverctrls = []
    );

    /**
     * Add attribute values to current attributes.
     *
     * @see http://php.net/manual/en/function.ldap-mod-add.php ldap_mod_add()
     */
    public function mod_add(string $dn, array $entry, array $serverctls = []): bool;

    /**
     * Delete attribute values from current attributes.
     *
     * @see http://php.net/manual/en/function.ldap-mod-del.php ldap_mod_del()
     */
    public function mod_del(string $dn, array $entry, array $serverctls = []): bool;

    /**
     * Replace attribute values with new ones.
     *
     * @see http://php.net/manual/en/function.ldap-mod-replace.php ldap_mod_replace()
     */
    public function mod_replace(string $dn, array $entry, array $serverctls = []): bool;

    /**
     * Batch and execute modifications on an LDAP entry.
     *
     * @see http://php.net/manual/en/function.ldap-modify-batch.php ldap_modify_batch()
     */
    public function modify_batch(string $dn, array $entry, array $serverctls = []): bool;

    /**
     * Modify an LDAP entry.
     *
     * @see http://php.net/manual/en/function.ldap-modify.php ldap_modify()
     */
    public function modify(string $dn, array $entry, array $serverctls = []): bool;

    /**
     * Read an entry.
     *
     * @return false|LdapResultInterface
     *
     * @see http://php.net/manual/en/function.ldap-list.php ldap_list()
     */
    public function read(
        string $base_dn,
        string $filter,
        array $attributes = ['*'],
        int $attrsonly = 0,
        int $sizelimit = -1,
        int $timelimit = -1,
        int $deref = LDAP_DEREF_NEVER,
        array $serverctrls = []
    );

    /**
     * Modify the name of an entry.
     *
     * @return bool
     *
     * @see http://php.net/manual/en/function.ldap-rename.php ldap_rename()
     */
    public function rename(string $dn, string $newrdn, string $newparent, bool $deleteoldrdn, array $serverctls = []);

    /**
     * Bind to LDAP directory using SASL.
     *
     * @see http://php.net/manual/en/function.ldap-sasl-bind.php ldap_sasl_bind()
     */
    public function sasl_bind(
        string $binddn = '',
        string $password = '',
        string $sasl_mech = '',
        string $sasl_realm = '',
        string $sasl_authc_id = '',
        string $sasl_authz_id = '',
        string $props = ''
    ): bool;

    /**
     * Search LDAP tree.
     *
     * @return false|LdapResultInterface
     *
     * @see http://php.net/manual/en/function.ldap-list.php ldap_list()
     */
    public function search(
        string $base_dn,
        string $filter,
        array $attributes = ['*'],
        int $attrsonly = 0,
        int $sizelimit = -1,
        int $timelimit = -1,
        int $deref = LDAP_DEREF_NEVER,
        array $serverctrls = []
    );

    /**
     * Set the value of the given option.
     *
     * @param mixed $newval
     *
     * @see http://php.net/manual/en/function.ldap-set-option.php ldap_set_option()
     */
    public function set_option(int $option, $newval): bool;

    /**
     * Set a callback function to do re-binds on referral chasing.
     *
     * @param callable $callback
     *
     * @see http://php.net/manual/en/function.ldap-set-rebind-proc.php ldap_set_rebind_proc()
     */
    public function set_rebind_proc($callback): bool;

    /**
     * Start TLS.
     *
     * @see http://php.net/manual/en/function.ldap-start-tls.php ldap_start_tls()
     */
    public function start_tls(): bool;

    /**
     * Unbind from LDAP directory.
     *
     * @see http://php.net/manual/en/function.ldap-unbind.php ldap_unbind()
     */
    public function unbind(): bool;

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd

    /**
     * Returns error handler attached to this instance.
     */
    public function getErrorHandler(): ErrorHandlerInterface;
}

// vim: syntax=php sw=4 ts=4 et:
