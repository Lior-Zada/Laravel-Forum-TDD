<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Spam;
use Exception;

class SpamTest extends TestCase
{

    public function test_text_for_spam()
    {
        $spam = new Spam();
        
        $this->assertFalse($spam->detect("No spam message here"));
        
        $this->withoutExceptionHandling()->expectException(Exception::class);

        $this->assertTrue($spam->detect("This is spam"));
    }
}
