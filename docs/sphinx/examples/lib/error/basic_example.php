<?php
/* [use] */
use function Korowai\Lib\Context\with;
use function Korowai\Lib\Error\emptyErrorHandler;

/* [makeTroubleFunction] */
function make_trouble()
{
    trigger_error('you have a problem');
}

/* [testCode] */
with(emptyErrorHandler())(function ($eh) {
    make_trouble();
});

// vim: syntax=php sw=4 ts=4 et:
