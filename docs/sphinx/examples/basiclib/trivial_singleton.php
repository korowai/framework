<?php
/* [code] */
/* [use] */
use Korowai\Lib\Basic\SingletonInterface;
use Korowai\Lib\Basic\Singleton;
/* [/use] */

/* [TrivialSingleton] */
class TrivialSingleton implements SingletonInterface
{
    use Singleton;
}
/* [/TrivialSingleton] */

/* [TestCode] */
$object = TrivialSingleton::getInstance();
/* [/TestCode] */
/* [/code] */

// vim: syntax=php sw=4 ts=4 et:
