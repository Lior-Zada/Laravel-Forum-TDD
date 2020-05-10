<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Inspections\Spam;
use Exception;

class SpamTest extends TestCase
{
    public function test_text_for_invalid_keywords()
    {
        $spam = new Spam();
        
        $this->assertFalse($spam->detect("No spam message here"));
        
        $this->withoutExceptionHandling()->expectException(Exception::class);

        $spam->detect("This is spam");
    }

    public function test_text_for_key_held_down()
    {
        $spam = new Spam();

        $this->withoutExceptionHandling()->expectException(Exception::class);
        
        $spam->detect('Hi there hhhhh');
    }
} 
