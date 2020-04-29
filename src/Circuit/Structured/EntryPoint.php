<?php

namespace Circuit\Structured; 

use Circuit\Interfaces;
use Circuit\Basic\Element;
use Circuit\Traits;

class EntryPoint extends Element implements Interfaces\Limitation, Interfaces\Element\EntryPoint {

    use Traits\IsEntryPoint;

    /**
     * @property  string 
     */
    protected $description = 'Limitation representing element';

    
}