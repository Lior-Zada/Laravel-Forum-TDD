<?php 

namespace App\Inspections;

use Exception;

class KeyHeldDown {

    protected $regularExpression = '/(.)\\1{4}/';

    public function detect($text)
    {
        if(preg_match($this->regularExpression , $text)){
            throw new Exception("Please don't hold down keys");
        }
    }
}