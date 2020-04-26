<?php 

namespace Circuit\Advanced; 

use Circuit\Basic\Entity;
use Circuit\Interfaces\{Connection,Structure};
use Circuit\Traits\{IsConnection, IsStructured, IsStructuredConnection};

class ComplexConnectionEntity extends ComplexElement implements Connection {
    
    use IsConnection;

    protected $description = 'An intermediate complex entity. Works like an element that connects other elements'; 

    public function __construct(array $elements, Structure $structure) {
        parent::__construct($structure);
        foreach ($elements as $element) {
            $this->connections[] = $this->connect($element);
        }
    }
}