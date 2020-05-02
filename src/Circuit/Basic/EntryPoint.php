<?php

namespace Circuit\Basic; 

use Circuit\Interfaces;
use Circuit\Basic\Element;
use Circuit\Traits;

class EntryPoint extends Element implements Interfaces\Limitation, Interfaces\EntryPoint {

    use Traits\IsEntryPoint;

    /**
     * @var  string 
     */
    protected $description = 'Limitation representing element';

    
}