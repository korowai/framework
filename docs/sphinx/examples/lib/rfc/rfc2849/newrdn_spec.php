<?php
/* [code] */
use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Testing\Rfclib\RuleDemo;

$demo = RuleDemo::create(Rfc2849::class, 'NEWRDN_SPEC');

$demo->matchAndReport("foo:");
$demo->matchAndReport("newrdn: dc=example\n");
$demo->matchAndReport("newrdn: dc=pomyłka\n");
$demo->matchAndReport("newrdn:: ZGM9YWNtZQ==\n");
$demo->matchAndReport("newrdn:: ZGM9***tZQ==\n");

/* [/code] */
// vim: syntax=php sw=4 ts=4 et:
