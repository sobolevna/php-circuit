<?php

namespace Circuit\Implementation\Basic; 

use Circuit\Interfaces;

class Core implements Interfaces\Core, Interfaces\Descriptable {

    /**
     * @property  string 
     */
    protected $description = 'What exactly it is';

    public function getDescription() : string {
        return $this->description;
    }
}