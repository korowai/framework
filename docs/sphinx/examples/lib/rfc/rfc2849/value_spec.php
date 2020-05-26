<?php
/* [code] */
use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Testing\Lib\Rfc\RuleDemo;

$demo = RuleDemo::create(Rfc2849::class, 'VALUE_SPEC');

$demo->matchAndReport("foo\n");
$demo->matchAndReport(": safe\n");
$demo->matchAndReport(":: YmFzZTY0\n");
$demo->matchAndReport(":< file:///cn.txt\n");
$demo->matchAndReport(": błąd\n");
$demo->matchAndReport(":: YmF***Y0\n");
$demo->matchAndReport(":< file:błąd.txt\n");

/* [/code] */
// vim: syntax=php sw=4 ts=4 et:
