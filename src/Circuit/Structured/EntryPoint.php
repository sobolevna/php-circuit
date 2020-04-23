<?php

namespace Circuit\Implementation\Basic; 

use Circuit\Interfaces;
use Circuit\Exceptions\ElementConnectionException;

class EntryPoint extends Element implements Interfaces\Limitation, Interfaces\Element\EntryPoint {

    /**
     * @property  string 
     */
    protected $description = 'Limitation representing element';

    public function connect(Element $element) {
        if (
            !($element instanceof Interfaces\Element\EntryPoint) || 
            !($element instanceof Interfaces\Element\Node)
        ) {
            throw new ElementConnectionException('EntryPoints can be connected to either EntryPoint or Node');
        }
        parent::connect($element);
    }
}