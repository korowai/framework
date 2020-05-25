<?php
/* [code] */
use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Testing\Lib\Rfc\Demo;

$demo = Demo::create(Rfc2849::class, 'ATTRVAL_SPEC');

$demo->matchAndReport("$$$\n");
$demo->matchAndReport("cn: safe\n");
$demo->matchAndReport("cn:: YmFzZTY0\n");
$demo->matchAndReport("cn:< file:///cn.txt\n");
$demo->matchAndReport("cn: błąd\n");
$demo->matchAndReport("cn:: YmF***Y0\n");
$demo->matchAndReport("cn:< file:błąd.txt\n");

/* [/code] */
// vim: syntax=php sw=4 ts=4 et:
