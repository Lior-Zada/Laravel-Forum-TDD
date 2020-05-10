<?php

namespace App\Inspections;

use Exception;
use App\Inspections\InvalidKeywords;

class Spam {

    protected $inspections = [
        InvalidKeywords::class,
        KeyHeldDown::class,
    ];

    public function detect($text){
       
        foreach ($this->inspections as $inspection) {
            // (new $inspection())->detect($text);
            app($inspection)->detect($text);
        }

        return false;
    }
}