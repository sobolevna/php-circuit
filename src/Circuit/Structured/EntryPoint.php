<?php

namespace Circuit\Structured; 

use Circuit\Interfaces;
use Circuit\Basic\Element;
use Circuit\Exceptions\ElementConnectionException;

class EntryPoint extends Element implements Interfaces\Limitation, Interfaces\Element\EntryPoint {

    /**
     * @property  string 
     */
    protected $description = 'Limitation representing element';

    public function connect(Interfaces\Element $element) : Interfaces\Connection {
        if (
            !($element instanceof Interfaces\Element\EntryPoint) && 
            !($element instanceof Interfaces\Element\Node)
        ) {
            throw new ElementConnectionException('EntryPoints can be connected to either EntryPoint or Node');
        }
        return parent::connect($element);
    }
}