<?php

namespace Tests\Feature;

use App\Trending;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;


class TrendingThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->trending = new Trending();

        $this->trending->reset();
    }

    public function test_it_increments_a_thread_score_each_time_it_is_read()
    {
        $this->assertEmpty($this->trending->get());
        
        $thread = create('App\Thread');
        
        $this->get($thread->path());

        $trending = $this->trending->get();
        
        $this->assertCount(1, $trending);
        
        $this->assertEquals($thread->title, $trending[0]->title);
    }


    public function test_a_thread_record_each_visit()
    {
        $thread = create('App\Thread', ['id' => 1]);

        $thread->visits()->reset();

        $this->assertSame(0, $thread->visits()->count());
        
        $thread->visits()->record();
        
        $this->assertEquals(1, $thread->visits()->count());
        
        $thread->visits()->record();
        
        $this->assertEquals(2, $thread->visits()->count());
    }
}
