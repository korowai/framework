<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Context;

/**
 * A context manager that wraps a PHP resource and releases it at exit.
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ResourceContextManager implements ContextManagerInterface
{
    private const DEFAULT_RESOURCE_DESTRUCTORS = [
        'bzip2' => '\\bzclose',
        'cubrid connection' => '\\cubrid_close',
        'persistent cubrid connection' => null,
        'cubrid request' => '\\cubrid_close_request',
        'cubrid lob' => '\\cubrid_lob_close',
        'cubrid lob2' => '\\cubrid_lob2_close',
        'curl' => '\\curl_close',
        'dba' => '\\dba_close',
        'dba persistent' => null,
        'dbase' => '\\dbase_close',
        'dbx_link_object' => '\\dbx_close',
        'dbx_result_object' => null,
        'xpath context' => null,
        'xpath object' => null,
        'fbsql link' => '\\fbsql_close',
        'fbsql plink' => null,
        'fbsql result' => '\\fbsql_free_result',
        'fdf' => '\\fdf_close',
        'ftp' => '\\ftp_close',
        'gd' => '\\imagedestroy',
        'gd font' => null,
        'gd PS encoding' => null,
        'gd PS font' => '\\imagepsfreefont',
        'GMP integer' => null,
        'imap' => '\\imap_close',
        'ingres' => '\\ingres_close',
        'ingres persistent' => null,
        'interbase blob' => null,
        'interbase link' => '\\ibase_close',
        'interbase link persistent' => null,
        'interbase query' => '\\ibase_free_query',
        'interbase result' => '\\ibase_free_result',
        'interbase transaction' => '\\ibase_free_transaction',
        'ldap link' => '\\ldap_close',
        'ldap result' => '\\ldap_free_result',
        'ldap result entry' => null,
        'SWFAction' => null,
        'SWFBitmap' => null,
        'SWFButton' => null,
        'SWFDisplayItem' => null,
        'SWFFill' => null,
        'SWFFont' => null,
        'SWFGradient' => null,
        'SWFMorph' => null,
        'SWFMovie' => null,
        'SWFShape' => null,
        'SWFSprite' => null,
        'SWFText' => null,
        'SWFTextField' => null,
        'mnogosearch agent' => null,
        'mnogosearch result' => null,
        'msql link' => '\\msql_close',
        'msql link persistent' => null,
        'msql query' => '\\msql_free_result',
        'mssql link' =>  '\\mssql_close',
        'mssql link persistent' => null,
        'mssql result' => '\\mssql_free_result',
        'mysql link' => '\\mysql_close',
        'mysql link persistent' => null,
        'mysql result' => '\\mysql_free_result',
        'oci8 collection' => '->free',
        'oci8 connection' => '\\oci_close',
        'oci8 lob' => '->free',
        'oci8 statement' => '\\oci_free_statement',
        'odbc link' => '\\odbc_close',
        'odbc link persistent' => null,
        'odbc result' => '\\odbc_free_result',
        'birdstep link' => null,
        'birdstep result' => null,
        'OpenSSL key' => '\\openssl_free_key',
        'OpenSSL X.509' => '\\openssl_x509_free',
        'pdf document' => '\\pdf_delete',
        'pdf image' => '\\pdf_close_image',
        'pdf object' => null,
        'pdf outline' => null,
        'pgsql large object' => '\\pg_lo_close',
        'pgsql link' => '\\pg_close',
        'pgsql link persistent' => null,
        'pgsql result' => '\\pg_free_result',
        'pgsql string' => null,
        'printer' => null,
        'printer brush' => null,
        'printer font' => null,
        'printer pen' => null,
        'pspell' => null,
        'pspell config' => null,
        'shmop' => '\\shmop_close',
        'sockets file descriptor set' => '\\close',
        'sockets i/o vector' => null,
        //'stream' => ['dir' => '\\closedir', 'STDIO' => '\fclose'],
        //'stream' => '\\pclose',
        'socket' => '\\fclose',
        'sybase-db link' => '\\sybase_close',
        'sybase-db link persistent' => null,
        'sybase-db result' => '\\sybase_free_result',
        'sybase-ct link' => '\\sybase_close',
        'sybase-ct link persistent' => null,
        'sybase-ct result' => '\\sybase_free_result',
        'sysvsem' => '\\sem_release',
        'sysvshm' => '\\shm_detach',
        'wddx' => '\\wddx_packet_end',
        'xml' => '\\xml_parser_free',
        'zlib' => '\\gzclose',
        'zlib.deflate' => null,
        'zlib.inflate' => null
    ];

    /**
     * @var resource
     * @psalm-readonly
     */
    private $resource;

    /**
     * @var callable|string|null
     * @psalm-readonly
     */
    private $destructor;

    /**
     * Initializes the context manager.
     *
     * @param  resource $resource The resource to be wrapped;
     * @param  callable|null $destructor
     *      Destructor function called from exitContext() to release the
     *      $resource. If not given or null, a default destructor will be used.
     */
    public function __construct($resource, ?callable $destructor = null)
    {
        $this->resource = $resource;
        $this->destructor = $destructor ?? self::getDefaultDestructor($resource);
    }

    /**
     * Returns the resource wrapped by this context manager.
     *
     * @return mixed
     * @psalm-mutation-free
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Returns the destructor that is used to release the resource.
     *
     * @return callable|string|null
     * @psalm-mutation-free
     */
    public function getDestructor()
    {
        return $this->destructor;
    }

    /**
     * {@inheritdoc}
     */
    public function enterContext()
    {
        return $this->resource;
    }

    /**
     * {@inheritdoc}
     */
    public function exitContext(\Throwable $exception = null) : bool
    {
        if ($this->destructor !== null) {
            call_user_func($this->destructor, $this->resource);
        }
        return false;
    }

    /**
     * Returns a callable that shall be used to release given $resource.
     *
     * @param mixed $resource
     * @return callable
     */
    private static function getDefaultDestructor($resource)
    {
        $type = get_resource_type($resource);
        $func = self::DEFAULT_RESOURCE_DESTRUCTORS[$type] ?? null;
        if (is_string($func) && substr($func, 0, 2) === '->') {
            $method = substr($func, 2);
            return self::mkObjectResourceDestructor($method);
        } elseif ($type === 'stream' && is_null($func)) {
            return self::getStreamResourceDestructor($resource);
        } else {
            return $func;
        }
    }

//    private static function destroyResource($resource) : void
//    {
//        if ($this->destructor !== null) {
//            call_user_func($this->destructor, $resource);
//        }
//    }

    private static function mkObjectResourceDestructor(string $method)
    {
        return function (object $resource) use ($method) {
            return call_user_func([$resource, $method]);
        };
    }

    private static function getStreamResourceDestructor($resource)
    {
        $meta = stream_get_meta_data($resource);
        if ($meta['stream_type'] === 'dir') {
            return '\\closedir';
        } else {
            return '\\fclose';
        }
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
