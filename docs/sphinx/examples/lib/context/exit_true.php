<?php
/* [code] */
/* [use] */
use Korowai\Lib\Context\ContextManagerInterface;
use function Korowai\Lib\Context\with;
/* [/use] */

/* [MyInt] */
class MyInt implements ContextManagerInterface
{
    public $value;
    public $handle;

    public function __construct(int $value, bool $handle = false)
    {
        $this->value = $value;
        $this->handle = $handle;
    }

    public function enterContext()
    {
        echo "enter: " . $this->value . "\n";
        return $this->value;
    }

    public function exitContext(?\Throwable $exception = null) : bool
    {
        echo "exit: " . $this->value . " (" . strtolower(gettype($exception)) . ")\n";
        return $this->handle;
    }
}
/* [/MyInt] */

/* [MyException] */
class MyException extends Exception
{
}
/* [/MyException] */

/* [test] */
with(new MyInt(1), new MyInt(2), new MyInt(3, true), new MyInt(4))(function (int ...$args) {
    throw new MyException('my error message');
});
/* [/test] */
/* [/code] */

// vim: syntax=php sw=4 ts=4 et:
