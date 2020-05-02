<?php

namespace Circuit\Structured; 

use Circuit\Interfaces;
use Circuit\Traits;

class StructuredNode extends StructuredElement implements Interfaces\Core, Interfaces\Element\Node {

    use Traits\IsNode;

    /**
     * @var  string 
     */
    protected $description = 'Core representing structured element';

    
}