<?php 

namespace Circuit\Advanced; 

use Circuit\Basic\Entity;
use Circuit\Interfaces\{Structure};
use Circuit\Traits\{IsConnection, IsStructured, IsStructuredConnection};

class AdvancedElement extends ComplexConnectionEntity {
    
    protected $description = 'An intermediate structured entity. Works like an element that connects other elements'; 

    protected $connectionClass = ComplexConnectionEntity::class;

    public function __construct(?Structure $structure, array $elements = []) {
        parent::__construct($elements, $structure);
    }
    
}