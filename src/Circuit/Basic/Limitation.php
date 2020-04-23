<?php

namespace Circuit\Implementation\Basic; 

use Circuit\Interfaces;

class Limitation implements Interfaces\Limitation, Interfaces\Descriptable {

    /**
     * @property  string 
     */
    protected $description = 'Everithing that is not it';

    public function getDescription() : string {
        return $this->description;
    }
}