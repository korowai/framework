<?php
/* [code] */
use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Testing\Lib\Rfc\RuleDemo;

$demo = RuleDemo::create(Rfc2849::class, 'NEWSUPERIOR_SPEC');

$demo->matchAndReport("foo:");
$demo->matchAndReport("newsuperior: dc=example,dc=org\n");
$demo->matchAndReport("newsuperior: dc=pomyłka,dc=org\n");
$demo->matchAndReport("newsuperior:: ZGM9YXNtZSxkYz1jb20=\n");
$demo->matchAndReport("newsuperior:: ZGM9YXNt***kYz1jb20=\n");

/* [/code] */
// vim: syntax=php sw=4 ts=4 et:
