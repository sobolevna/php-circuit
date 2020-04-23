<?php

namespace Circuit\Implementation\Basic; 

use Circuit\Interfaces;
use Circuit\Traits\{HasDescription, IsStructured};

class Structure implements Interfaces\Structure, Interfaces\Descriptable {

    use HasDescription, IsStructured;

    /**
     * @property  string 
     */
    protected $description = 'Basic structure. Just elements and connections';    

    public function __construct(array $elements = [], array $connections = []) {
        $this->structureElements = $elements;
        $this->structureConnections = $connections;
    }

    
}