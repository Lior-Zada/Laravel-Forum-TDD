<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;
         
    public function testa_user_can_search_threads()
    {

        config(['scout.driver' => 'algolia']);

        $keyword = 'zeKeyWord';
        create('App\Thread', ['title' => "Some text with $keyword"], 2);
        create('App\Thread', [], 2);

        do {
            sleep(.25);
            $results = $this->getJson("/threads/search?query=$keyword")->json()['data'];
        } while (empty($results));

        $this->assertCount(2, $results);

        // the laravel Scout makes use of laravel Macroable, which enables to dynamically on the fly
        // hook into a class and extended its functionallity. therefore we can use this searchable

        Thread::latest()->take(4)->unsearchable();
     }
}
