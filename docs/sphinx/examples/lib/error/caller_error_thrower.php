<?php
/* [code] */
/* [use] */
use function Korowai\Lib\Context\with;
use function Korowai\Lib\Error\callerExceptionErrorHandler;
/* [/use] */

/* [trigger] */
function trigger()
{
    with(callerExceptionErrorHandler(\ErrorException::class))(function ($eh) {
        printf("trigger_error() called at: %s: %d\n", basename(__file__), __line__ + 1);
        @trigger_error("error message");
    });
}
/* [/trigger] */

/* [try-catch] */
try {
    printf("trigger() called at: %s: %d\n", basename(__file__), __line__ + 1);
    trigger();
} catch (\ErrorException $e) {
    printf("error occured at %s: %d: (%s)\n", basename($e->getFile()), $e->getLine(), $e->getMessage());
    exit(1);
}
/* [/try-catch] */
/* [/code] */

// vim: syntax=php sw=4 ts=4 et:
