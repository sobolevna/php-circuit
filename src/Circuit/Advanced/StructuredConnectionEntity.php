<?php 

namespace Circuit\Advanced; 

use Circuit\Basic\Entity;
use Circuit\Interfaces\Connection;
use Circuit\Interfaces\{Node, EntryPoint, EmptyField};
use Circuit\Traits\{IsConnection, IsStructured, IsStructuredConnection};

class StructuredConnectionEntity extends Element implements Interfaces\Connection {
    
    use IsConnection;

    protected $description = 'An intermediate structured entity. Works like an element that connects other elements'; 

    public function __construct(array $elements, Node $core, EntryPoint $limitation, EmptyField $particularity) {
        parent::__construct($core, $limitation, $particularity);
        foreach ($elements as $element) {
            $this->connections[] = $this->connect($element);
        }
    }
}