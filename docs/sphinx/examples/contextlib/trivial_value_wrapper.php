<?php
/* [code] */
/* [use] */
use Korowai\Lib\Context\TrivialValueWrapper;
use function Korowai\Lib\Context\with;
/* [/use] */

/* [test] */
with(new TrivialValueWrapper('argument value'))(function (string $value) {
    echo $value . "\n";
});
/* [/test] */
/* [/code] */

// vim: syntax=php sw=4 ts=4 et:
