<?php 

namespace App\Inspections;

use Exception;

class InvalidKeywords {

    protected  $invalidKeywords = [
        'This is spam'
    ];

    public function detect($text)
    {
        foreach ($this->invalidKeywords as $keyword) {
            if(stripos($text, $keyword) !== false){
                throw new Exception("Your reply contains spam");
            }
        }
    }
}