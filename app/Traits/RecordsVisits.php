<?php

namespace App\Traits;

use Illuminate\Support\Facades\Redis;

trait RecordsVisits {

    public function recordVisit()
    {
        Redis::incr($this->visitsCacheKey());

        return $this;
    }

    public function visits()
    {
        return Redis::get($this->visitsCacheKey()) ?? 0;
    }

    public function resetVisits()
    {
        Redis::del($this->visitsCacheKey());
    }

    protected function visitsCacheKey()
    {
        return "threads.{$this->id}.visits";
    }
}