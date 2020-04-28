<?php

namespace Circuit\Structured; 

use Circuit\Interfaces;
use Circuit\Basic\Element;
use Circuit\Exceptions\ElementConnectionException;

class EmptyField extends Element implements Interfaces\Particularity, Interfaces\Element\EmptyField {

    /**
     * @property  string 
     */
    protected $description = 'Core representing element';

    public function connect(Interfaces\Element $element) : Interfaces\Connection {
        if (!$element instanceof Node) {
            throw new ElementConnectionException('EmptyFields can be connected only to Nodes');
        }
        return parent::connect($element);
    }
}