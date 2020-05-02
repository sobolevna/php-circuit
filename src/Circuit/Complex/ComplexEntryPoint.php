<?php

namespace Circuit\Complex; 

use Circuit\Interfaces;
use Circuit\Traits;

class ComplexEntryPoint extends ComplexElement implements Interfaces\Limitation, Interfaces\Element\EntryPoint {

    use Traits\IsEntryPoint;

    /**
     * @var  string 
     */
    protected $description = 'Limitation representing complex element';
    
}