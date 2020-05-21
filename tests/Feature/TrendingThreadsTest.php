<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Tests\TestCase;

use function GuzzleHttp\json_decode;

class TrendingThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Redis::del('trending_threads');
    }

    public function test_it_increments_a_thread_score_each_time_it_is_read()
    {
        $this->assertEmpty(Redis::zrevrange('trending_threads', 0, -1));
        
        $thread = create('App\Thread');
        
        $this->get($thread->path());

        $trending = Redis::zrevrange('trending_threads', 0, -1);
        
        $this->assertCount(1, $trending);
        
        $this->assertEquals($thread->title, json_decode($trending[0])->title);
    }
}
