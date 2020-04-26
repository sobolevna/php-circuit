<?php

namespace Circuit\Basic; 

use Circuit\Interfaces\{Core, Connection};
use Circuit\Interfaces\Element\Node;
use Circuit\Basic\Element;
use Circuit\Exceptions\ElementConnectionException;

class EmptyField extends Element implements Core, Node {

    /**
     * @property  string 
     */
    protected $description = 'Core representing element';

    public function connect(Element $element) : Connection {
        if (!$element instanceof Node) {
            throw new ElementConnectionException('EmptyFields can be connected only to Nodes');
        }
        return parent::connect($element);
    }
}