<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Context;

use Korowai\Testing\TestCase;

use Korowai\Lib\Context\ResourceContextManager;
use Korowai\Lib\Context\ContextManagerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Context\ResourceContextManager
 */
final class ResourceContextManagerTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function prepareForGetResourceDestructor($resource, $type) : void
    {
        $this->getFunctionMock('Korowai\\Lib\\Context', 'get_resource_type')
             ->expects($this->once())
             ->with($resource)
             ->willReturn($type);
    }

    public function test__implements__ContextManagerInterface()
    {
        $this->assertImplementsInterface(ContextManagerInterface::class, ResourceContextManager::class);
    }

    public function test__construct()
    {
        $manager = new ResourceContextManager('foo');
        $this->assertSame('foo', $manager->getResource());
    }

    public function test__enterContext()
    {
        $manager = new ResourceContextManager('foo');
        $this->assertSame('foo', $manager->enterContext());
    }

    public function test__exitContext__withNonResource()
    {
//        $arg = ['foo'];
//
//        $cm = $this->getMockBuilder(ResourceContextManager::class)
//                   ->disableOriginalConstructor()
//                   ->setMethods(['destroyResource', 'getResource'])
//                   ->getMock();
//
//        $cm->expects($this->once())
//           ->method('getResource')
//           ->willReturn($arg);
//
//        $cm->expects($this->never())
//           ->method('destroyResource');
//
//        $this->getFunctionMock('Korowai\\Lib\\Context', 'is_resource')
//             ->expects($this->once())
//             ->with($arg)
//             ->willReturn(false);
//
//        $this->assertFalse($cm->exitContext(null));
        $this->markTestIncomplete('Test not implemented yet');
    }

    /**
     * @runInSeparateProcess
     */
    public function test__exitContext__withResource()
    {
//        $arg = ['foo'];
//
//        $cm = $this->getMockBuilder(ResourceContextManager::class)
//                   ->disableOriginalConstructor()
//                   ->setMethods(['destroyResource', 'getResource'])
//                   ->getMock();
//
//        $cm->expects($this->once())
//           ->method('getResource')
//           ->willReturn($arg);
//
//        $cm->expects($this->once())
//           ->method('destroyResource')
//           ->with($arg);
//
//        $this->getFunctionMock('Korowai\\Lib\\Context', 'is_resource')
//             ->expects($this->once())
//             ->with($arg)
//             ->willReturn(true);
//
//        $this->assertFalse($cm->exitContext(null));
        $this->markTestIncomplete('Test not implemented yet');
    }

    /**
     * @runInSeprarateProcess
     */
    public function test__exitContext__withResource_and_nullResourceDtor()
    {
//        $arg = ['foo'];
//
//
//        $cm = $this->getMockBuilder(ResourceContextManager::class)
//                   ->disableOriginalConstructor()
//                   ->setMethods(['getResourceDestructor', 'getResource'])
//                   ->getMock();
//
//        $cm->expects($this->once())
//           ->method('getResource')
//           ->willReturn($arg);
//
//        $cm->expects($this->once())
//           ->method('getResourceDestructor')
//           ->with($arg)
//           ->willReturn(null);
//
//        $this->getFunctionMock('Korowai\\Lib\\Context', 'is_resource')
//             ->expects($this->once())
//             ->with($arg)
//             ->willReturn(true);
//
//        $this->assertFalse($cm->exitContext(null));
        $this->markTestIncomplete('Test not implemented yet');
    }

    /**
     * @runInSeprarateProcess
     */
    public function test__exitContext__withResource_and_NonNullResourceDtor()
    {
//        $arg = ['foo'];
//        $deleted = null;
//
//        $cm = $this->getMockBuilder(ResourceContextManager::class)
//                   ->disableOriginalConstructor()
//                   ->setMethods(['getResourceDestructor', 'getResource'])
//                   ->getMock();
//
//        $cm->expects($this->once())
//           ->method('getResource')
//           ->willReturn($arg);
//
//        $cm->expects($this->once())
//           ->method('getResourceDestructor')
//           ->with($arg)
//           ->willReturn(function ($res) use (&$deleted) {
//               $deleted = $res;
//           });
//
//        $this->getFunctionMock('Korowai\\Lib\\Context', 'is_resource')
//             ->expects($this->once())
//             ->with($arg)
//             ->willReturn(true);
//
//        $this->assertFalse($cm->exitContext(null));
//        $this->assertSame($deleted, $arg);
        $this->markTestIncomplete('Test not implemented yet');
    }

    public static function prov__getResourceDestructor() : array
    {
        return [
            ['bzip2', '\\bzclose'],
            ['cubrid connection', '\\cubrid_close'],
            ['persistent cubrid connection', null],
            ['cubrid request', '\\cubrid_close_request'],
            ['cubrid lob', '\\cubrid_lob_close'],
            ['cubrid lob2', '\\cubrid_lob2_close'],
            ['curl', '\\curl_close'],
            ['dba', '\\dba_close'],
            ['dba persistent', null],
            ['dbase', '\\dbase_close'],
            ['dbx_link_object', '\\dbx_close'],
            ['dbx_result_object', null],
            ['xpath context', null],
            ['xpath object', null],
            ['fbsql link', '\\fbsql_close'],
            ['fbsql plink', null],
            ['fbsql result', '\\fbsql_free_result'],
            ['fdf', '\\fdf_close'],
            ['ftp', '\\ftp_close'],
            ['gd', '\\imagedestroy'],
            ['gd font', null],
            ['gd PS encoding', null],
            ['gd PS font', '\\imagepsfreefont'],
            ['GMP integer', null],
            ['imap', '\\imap_close'],
            ['ingres', '\\ingres_close'],
            ['ingres persistent', null],
            ['interbase blob', null],
            ['interbase link', '\\ibase_close'],
            ['interbase link persistent', null],
            ['interbase query', '\\ibase_free_query'],
            ['interbase result', '\\ibase_free_result'],
            ['interbase transaction', '\\ibase_free_transaction'],
            ['ldap link', '\\ldap_close'],
            ['ldap result', '\\ldap_free_result'],
            ['ldap result entry', null],
            ['SWFAction', null],
            ['SWFBitmap', null],
            ['SWFButton', null],
            ['SWFDisplayItem', null],
            ['SWFFill', null],
            ['SWFFont', null],
            ['SWFGradient', null],
            ['SWFMorph', null],
            ['SWFMovie', null],
            ['SWFShape', null],
            ['SWFSprite', null],
            ['SWFText', null],
            ['SWFTextField', null],
            ['mnogosearch agent', null],
            ['mnogosearch result', null],
            ['msql link', '\\msql_close'],
            ['msql link persistent', null],
            ['msql query', '\\msql_free_result'],
            ['mssql link', '\\mssql_close'],
            ['mssql link persistent', null],
            ['mssql result', '\\mssql_free_result'],
            ['mysql link', '\\mysql_close'],
            ['mysql link persistent', null],
            ['mysql result', '\\mysql_free_result'],
            ['oci8 connection', '\\oci_close'],
            ['oci8 statement', '\\oci_free_statement'],
            ['odbc link', '\\odbc_close'],
            ['odbc link persistent', null],
            ['odbc result', '\\odbc_free_result'],
            ['birdstep link', null],
            ['birdstep result', null],
            ['OpenSSL key', '\\openssl_free_key'],
            ['OpenSSL X.509', '\\openssl_x509_free'],
            ['pdf document', '\\pdf_delete'],
            ['pdf image', '\\pdf_close_image'],
            ['pdf object', null],
            ['pdf outline', null],
            ['pgsql large object', '\\pg_lo_close'],
            ['pgsql link', '\\pg_close'],
            ['pgsql link persistent', null],
            ['pgsql result', '\\pg_free_result'],
            ['pgsql string', null],
            ['printer', null],
            ['printer brush', null],
            ['printer font', null],
            ['printer pen', null],
            ['pspell', null],
            ['pspell config', null],
            ['shmop', '\\shmop_close'],
            ['sockets file descriptor set', '\\close'],
            ['sockets i/o vector', null],
            ['socket', '\\fclose'],
            ['sybase-db link', '\\sybase_close'],
            ['sybase-db link persistent', null],
            ['sybase-db result', '\\sybase_free_result'],
            ['sybase-ct link', '\\sybase_close'],
            ['sybase-ct link persistent', null],
            ['sybase-ct result', '\\sybase_free_result'],
            ['sysvsem', '\\sem_release'],
            ['sysvshm', '\\shm_detach'],
            ['wddx', '\\wddx_packet_end'],
            ['xml', '\\xml_parser_free'],
            ['zlib', '\\gzclose'],
            ['zlib.deflate', null],
            ['zlib.inflate', null],
        ];
    }

    /**
     * @dataProvider prov__getResourceDestructor
     * @runInSeparateProcess
     */
    public function test__getResourceDestructor(string $type, $expect) : void
    {
        $this->prepareForGetResourceDestructor('foo', $type);
        $this->assertSame($expect, ResourceContextManager::getResourceDestructor('foo'));
    }

    /**
     * @runInSeprarateProcess
     */
    public function test__getResourceDestructor__oci8_collection()
    {
        $res = new class {
            public $destroyed = false;
            public function free()
            {
                $this->destroyed = true;
            }
        };

        $this->prepareForGetResourceDestructor($res, 'oci8 collection');
        $dtor = ResourceContextManager::getResourceDestructor($res);

        $this->assertIsCallable($dtor);

        call_user_func($dtor, $res);

        $this->assertTrue($res->destroyed);
    }

    /**
     * @runInSeprarateProcess
     */
    public function test__getResourceDestructor__oci8_lob()
    {
        $res = new class {
            public $destroyed = false;
            public function free()
            {
                $this->destroyed = true;
            }
        };

        $this->prepareForGetResourceDestructor($res, 'oci8 lob');
        $dtor = ResourceContextManager::getResourceDestructor($res);

        $this->assertIsCallable($dtor);

        call_user_func($dtor, $res);

        $this->assertTrue($res->destroyed);
    }

    /**
     * @runInSeprarateProcess
     */
    public function test__getResourceDestructor__dir_stream()
    {
        $this->prepareForGetResourceDestructor('foo', 'stream');
        $this->getFunctionMock('Korowai\\Lib\\Context', 'stream_get_meta_data')
             ->expects($this->once())
             ->with('foo')
             ->willReturn(['stream_type' => 'dir']);
        $this->assertEquals('\\closedir', ResourceContextManager::getResourceDestructor('foo'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__getResourceDestructor__stream()
    {
        $this->prepareForGetResourceDestructor('foo', 'stream');
        $this->getFunctionMock('Korowai\\Lib\\Context', 'stream_get_meta_data')
             ->expects($this->once())
             ->with('foo')
             ->willReturn(['stream_type' => 'baz']);
        $this->assertEquals('\\fclose', ResourceContextManager::getResourceDestructor('foo'));
    }
}

// vim: syntax=php sw=4 ts=4 et:
