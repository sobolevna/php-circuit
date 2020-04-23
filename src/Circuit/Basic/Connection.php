<?php

namespace Circuit\Implementation\Basic; 

use Circuit\Interfaces;
use Circuit\Traits\HasDescription;

class Connection implements Interfaces\Connection, Interfaces\Descriptable {

    use HasDescription;

    /**
     * @property  string 
     */
    protected $description = 'Basic connection';
    /**
     * @propery Element[] $elements
     */
    protected $elements = [];

    /**
     * @param Element[] $elements
     */
    public function __construct(array $elements) {
        $this->elements = $elements;
    }

    public function getElements() : array {
        return $this->elements;
    }
}