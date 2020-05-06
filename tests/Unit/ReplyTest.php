<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
// use PHPUnit\Framework\TestCase; // this is the default, and its not working!!!

class ReplyTest extends TestCase
{

    // We use database in memory, so we have to use this:
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->reply = create('App\Reply');
    }

    function test_it_has_an_owner()
    {
        $this->assertInstanceOf('App\User', $this->reply->owner);
    }
}
