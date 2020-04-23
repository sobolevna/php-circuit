<?php

namespace Circuit\Implementation\Basic; 

use Circuit\Interfaces;
use Circuit\Exceptions\ElementConnectionException;

class EmptyField extends Element implements Interfaces\Core, Interfaces\Element\Node {

    /**
     * @property  string 
     */
    protected $description = 'Core representing element';

    public function connect(Element $element) {
        if (!$element instanceof Interfaces\Element\Node) {
            throw new ElementConnectionException('EmptyFields can be connected only to Nodes');
        }
        parent::connect($element);
    }
}