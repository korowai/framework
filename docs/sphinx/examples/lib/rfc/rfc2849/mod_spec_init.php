<?php
/* [code] */
use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Testing\Lib\Rfc\Demo;

$demo = Demo::create(Rfc2849::class, 'MOD_SPEC_INIT');

$demo->matchAndReport("foo:");
$demo->matchAndReport("add: cn\n");
$demo->matchAndReport("add: błąd\n");
$demo->matchAndReport("add: cn;lang-pl\n");
$demo->matchAndReport("add: cn;lang-błąd\n");

/* [/code] */
// vim: syntax=php sw=4 ts=4 et:
