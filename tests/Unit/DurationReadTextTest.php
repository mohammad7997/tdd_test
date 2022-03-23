<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Helpers\DurationReadText;

class DurationReadTextTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     * @test
     */
    public function test_example()
    {
        // 1 seconde per read

        $text = 'this is a test text';

        $duration = new DurationReadText($text);

        $this->assertEquals(5,$duration->get_seconde());
        $this->assertEquals(5/60,$duration->get_minutes());
    }
}
