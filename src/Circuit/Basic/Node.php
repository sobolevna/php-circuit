<?php

namespace Circuit\Basic; 

use Circuit\Interfaces;
use Circuit\Basic\Element;
use Circuit\Exceptions\ElementConnectionException;

class Node extends Element implements Interfaces\Core, Interfaces\Node {

    /**
     * @var  string 
     */
    protected $description = 'Core representing element';

    /**
     * @param Element $element
     * @throws ElementConnectionException
     */
    public function connect(Interfaces\Element $element) : Interfaces\Connection{
        if (
            !($element instanceof Interfaces\EntryPoint) && 
            !($element instanceof Interfaces\EmptyField)&&
            !($element instanceof Interfaces\Node)
        ) {            
            throw new ElementConnectionException('Nodes can be connected to either EntryPoint or EmptyField or another Node');
        }
        return parent::connect($element);
    }
}