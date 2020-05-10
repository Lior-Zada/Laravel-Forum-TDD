<?php

namespace App;

use Exception;

class Spam {

    public function detect($text){
       
        $this->detectInvalidKeywords($text);

        return false;
    }


    public function detectInvalidKeywords($text)
    {
        $invalidKeywords = [
            'This is spam'
        ];

        foreach ($invalidKeywords as $keyword) {
            if(stripos($text, $keyword) !== false){
                throw new Exception("Your reply contains spam");
            }
        }
    }

}