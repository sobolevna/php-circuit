<?php

namespace Circuit\Structured; 

use Circuit\Interfaces;
use Circuit\Traits;

class EntryPoint extends Element implements Interfaces\Limitation, Interfaces\EntryPoint {

    use Traits\IsEntryPoint;

    /**
     * @var  string 
     */
    protected $description = 'Limitation representing structured element';
    
}