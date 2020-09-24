<?php

/**
 * @coversNothing
 */
final class AssertHasPregCapturesTest extends \Korowai\Testing\TestCase
{
    private $regexp;
    private $subject;
    private $matches;

    public function setUp() : void
    {
        $this->regexp = '/(?<name>\w+) (?<surname>\w+)(?:, (?<age>\d+))?(?:, (?<city>\w+))?/';
        $this->subject = 'John Smith, London';
        preg_match($this->regexp, $this->subject, $this->matches, PREG_UNMATCHED_AS_NULL);
    }

    public function testAssertHasPregCaptures()
    {
        // assert that:
        $this->assertHasPregCaptures([
            'name'    => 'John',  // - name is 'John' (ok),
            'surname' => 'Smith', // - surname is 'Smith' (ok),
            'age'     =>  false,  // - age is absent (ok),
            'city'    =>  true    // - city is present (ok).
        ], $this->matches);
    }

    public function testAssertHasPregCapturesFailure()
    {
        // assert that:
        $this->assertHasPregCaptures([
            'name'    => 'John',  // - name is 'John' (ok),
            'surname' => 'Brown', // - surname is 'Brown' (fail),
            'age'     =>  true,   // - age is present (fail),
            'city'    =>  false   // - city is absent (fail).
        ], $this->matches);
    }
}
