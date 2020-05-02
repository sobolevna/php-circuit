<?php

namespace Circuit\Structured; 

use Circuit\Interfaces;
use Circuit\Traits;

class Node extends Element implements Interfaces\Core, Interfaces\Node {

    use Traits\IsNode;

    /**
     * @var  string 
     */
    protected $description = 'Core representing structured element';

    
}