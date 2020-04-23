<?php

namespace Circuit\Basic; 

use Circuit\Interfaces;
use Circuit\Basic\Element;
use Circuit\Exceptions\ElementConnectionException;

class Node extends Element implements Interfaces\Core, Interfaces\Element\Node {

    /**
     * @property  string 
     */
    protected $description = 'Core representing element';

    /**
     * @param Element $element
     * @throws ElementConnectionException
     */
    public function connect(Interfaces\Element $element) : Interfaces\Connection{
        if (
            !($element instanceof Interfaces\Element\EntryPoint) || 
            !($element instanceof Interfaces\Element\EmptyField)|| 
            !($element instanceof Interfaces\Element\Node)
        ) {
            throw new ElementConnectionException('Nodes can be connected to either EntryPoint or EmptyField or another Node');
        }
        return parent::connect($element);
    }
}