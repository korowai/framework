<?php
/* [code] */
use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Testing\Lib\Rfc\Demo;

$demo = Demo::create(Rfc2849::class, 'CONTROL');

$demo->matchAndReport("foo:");
$demo->matchAndReport("control: 1.2.3\n");
$demo->matchAndReport("control: 1.2.3: safe\n");
$demo->matchAndReport("control: 1.2.3:: YmFzZTY0\n");
$demo->matchAndReport("control: 1.2.3 true\n");
$demo->matchAndReport("control: 1.2.3 true: safe\n");
$demo->matchAndReport("control: 1.2.3 true:: YmFzZTY0\n");
$demo->matchAndReport("control: @#$\n");
$demo->matchAndReport("control: 1.2.3: błąd\n");
$demo->matchAndReport("control: 1.2.3:: Ym***TY0\n");
$demo->matchAndReport("control: 1.2.3 foo\n");
$demo->matchAndReport("control: 1.2.3 foo: safe\n");
$demo->matchAndReport("control: 1.2.3 true: błąd\n");
$demo->matchAndReport("control: 1.2.3 true:: Ym***TY0\n");

/* [/code] */
// vim: syntax=php sw=4 ts=4 et:
