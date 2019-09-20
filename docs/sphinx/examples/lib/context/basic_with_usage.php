<?php
/* [code] */
/* [use] */
use function Korowai\Lib\Context\with;
/* [/use] */

/* [test] */
with(fopen(__DIR__.'/hello.txt', 'r'))(function ($fd) {
  echo stream_get_contents($fd)."\n";
});
/* [/test] */
/* [/code] */

// vim: syntax=php sw=4 ts=4 et:
