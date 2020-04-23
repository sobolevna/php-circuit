<?php

namespace Circuit\Implementation\Basic; 

use Circuit\Interfaces;

class Particularity implements Interfaces\Particularity {

    /**
     * @property  string 
     */
    protected $description = 'What differs it from others';

    public function getDescription() : string {
        return $this->description;
    }
}