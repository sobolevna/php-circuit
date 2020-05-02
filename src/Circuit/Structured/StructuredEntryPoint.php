<?php

namespace Circuit\Structured; 

use Circuit\Interfaces;
use Circuit\Traits;

class StructuredEntryPoint extends StructuredElement implements Interfaces\Limitation, Interfaces\Element\EntryPoint {

    use Traits\IsEntryPoint;

    /**
     * @var  string 
     */
    protected $description = 'Limitation representing structured element';
    
}