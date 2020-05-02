<?php

namespace Circuit\Complex; 

use Circuit\Interfaces;
use Circuit\Traits;

class ComplexNode extends ComplexElement implements Interfaces\Core, Interfaces\Element\Node {

    use Traits\IsNode;

    /**
     * @var  string 
     */
    protected $description = 'Core representing complex element';

    
}