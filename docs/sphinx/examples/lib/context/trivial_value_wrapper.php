<?php
/* [use] */
use Korowai\Lib\Context\TrivialValueWrapper;
use function Korowai\Lib\Context\with;

/* [withTrivialValueWrapperEchoValue] */
with(new TrivialValueWrapper('argument value'))(function (string $value) {
    echo $value . "\n";
});

// vim: syntax=php sw=4 ts=4 et:
