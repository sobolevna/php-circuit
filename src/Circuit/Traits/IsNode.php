<?php 

namespace Circuit\Traits; 

use Circuit\Interfaces;
use Circuit\Exceptions\ElementConnectionException;

trait IsNode {
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