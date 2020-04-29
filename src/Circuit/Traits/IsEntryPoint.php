<?php 

namespace Circuit\Traits; 

use Circuit\Interfaces;
use Circuit\Exceptions\ElementConnectionException;

trait IsEntryPoint {
    /**
     * @param Interfaces\Element $element
     * @throws ElementConnectionException
     * @return Interfaces\Connection
     */
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