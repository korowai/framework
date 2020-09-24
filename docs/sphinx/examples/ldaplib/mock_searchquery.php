<?php
/* [code] */
/* [use] */
use PHPUnit\Framework\TestCase;
use Korowai\Lib\Ldap\Adapter\SearchQueryInterface;
use Korowai\Lib\Ldap\Adapter\ResultEntryInterface;
use Korowai\Lib\Ldap\Adapter\Mock\Result;
/* [/use] */

/* [functions] */
function getPosixAccounts(SearchQueryInterface $query) : array
{
    function isPosixAccount(ResultEntryInterface $entry) : bool
    {
        $attributes = $entry->getAttributes();
        $objectclasses = array_map('strtolower', $attributes['objectclass'] ?? []);
        return in_array('posixaccount', $objectclasses);
    }

    $result = $query->getResult();
    $users = [];
    foreach ($result->getResultEntryIterator() as $entry) {
        if (isPosixAccount($entry)) {
            $users[] = $entry;
        }
    }
    return $users;
}
/* [/functions] */

/* [testcase] */
final class TestGetPosixAccounts extends TestCase
{
    public function test()
    {
        /* [result] */
        // this is used instead of a long chain of mocks...
        $result = Result::make([
            [
                'dn' => 'cn=admin,dc=org',
                'cn' => 'admin',
                'objectClass' => ['person']
            ], [
                'dn' => 'uid=jsmith,dc=org',
                'uid' => 'jsmith',
                'objectClass' => ['posixAccount']
            ], [
                'dn' => 'uid=gbrown,dc=org',
                'uid' => 'gbrown',
                'objectClass' => ['posixAccount']
            ],
        ]);
        /* [/result] */

        /* [queryMock] */
        $queryMock = $this->getMockBuilder(SearchQueryInterface::class)
                          ->getMockForAbstractClass();
        $queryMock->expects($this->once())
                  ->method('getResult')
                  ->with()
                  ->willReturn($result);
        /* [/queryMock] */

        $entries = getPosixAccounts($queryMock);

        $this->assertCount(2, $entries);
        $this->assertInstanceOf(ResultEntryInterface::class, $entries[0]);
        $this->assertInstanceOf(ResultEntryInterface::class, $entries[1]);
        $this->assertSame('uid=jsmith,dc=org', $entries[0]->getDn());
        $this->assertSame(['uid' => ['jsmith'], 'objectclass' => ['posixAccount']], $entries[0]->getAttributes());
        $this->assertSame('uid=gbrown,dc=org', $entries[1]->getDn());
        $this->assertSame(['uid' => ['gbrown'], 'objectclass' => ['posixAccount']], $entries[1]->getAttributes());
    }
}
/* [/testcase] */

/* [test] */
$testCase = new TestGetPosixAccounts;
$testCase->test();
/* [/test] */
/* [/code] */

// vim: syntax=php sw=4 ts=4 et:
