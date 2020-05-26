<?php
/* [code] */
use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Testing\Lib\Rfc\RuleDemo;

$demo = RuleDemo::create(Rfc2849::class, 'VERSION_SPEC');

$demo->matchAndReport("foo:");
$demo->matchAndReport("version: 123\n");
$demo->matchAndReport("version: 12x\n");

/* [/code] */
// vim: syntax=php sw=4 ts=4 et:
