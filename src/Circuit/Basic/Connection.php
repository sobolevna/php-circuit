<?php

namespace Circuit\Basic; 

use Circuit\Interfaces;
use Circuit\Traits\{HasDescription, IsConnection};

class Connection implements Interfaces\Connection, Interfaces\Descriptable {

    use HasDescription, IsConnection;

    /**
     * @var  string 
     */
    protected $description = 'Basic connection';
    
    /**
     * @param Element[] $elements
     */
    public function __construct(array $elements) {
        $this->elements = $elements;
        $this->addConnectionToElements($elements);
    }
    
}