<?php
/* [code] */
use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Testing\Lib\Rfc\RuleDemo;

$demo = RuleDemo::create(Rfc2849::class, 'CHANGERECORD_INIT');

$demo->matchAndReport("foo:");
$demo->matchAndReport("changetype: add\n");
$demo->matchAndReport("changetype: bar\n");

/* [/code] */
// vim: syntax=php sw=4 ts=4 et:
