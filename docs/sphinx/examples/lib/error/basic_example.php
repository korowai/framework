<?php
/* [code] */
/* [use] */
use function Korowai\Lib\Context\with;
use function Korowai\Lib\Error\emptyErrorHandler;
/* [/use] */

/* [trouble] */
function trouble()
{
    trigger_error('you have a problem');
}
/* [/trouble] */

/* [test] */
with(emptyErrorHandler())(function ($eh) {
    trouble();
});
/* [/test] */
/* [/code] */

// vim: syntax=php sw=4 ts=4 et:
