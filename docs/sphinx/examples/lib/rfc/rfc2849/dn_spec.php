<?php
/* [code] */
use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Testing\Lib\Rfc\RuleDemo;

$demo = RuleDemo::create(Rfc2849::class, 'DN_SPEC');

$demo->matchAndReport("foo:");
$demo->matchAndReport("dn: dc=example,dc=org\n");
$demo->matchAndReport("dn: dc=pomyłka,dc=org\n");
$demo->matchAndReport("dn:: ZGM9YXNtZSxkYz1jb20=\n");
$demo->matchAndReport("dn:: ZGM9YXNt***kYz1jb20=\n");

/* [/code] */
// vim: syntax=php sw=4 ts=4 et:
