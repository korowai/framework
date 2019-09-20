<?php
/* [use] */
use function Korowai\Lib\Context\with;
use Korowai\Lib\Error\EmptyErrorHandler;

/* [makeTroubleFunction] */
function make_trouble()
{
    trigger_error('you have a problem');
}

/* [testCode] */
with(EmptyErrorHandler::getInstance())(function ($eh) {
    make_trouble();
});

// vim: syntax=php sw=4 ts=4 et:
