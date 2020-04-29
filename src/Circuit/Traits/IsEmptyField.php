<?php 

namespace Circuit\Traits; 

use Circuit\Interfaces;
use Circuit\Exceptions\ElementConnectionException;

trait IsEmptyField {
    /**
     * @param Interfaces\Element $element
     * @throws ElementConnectionException
     * @return Interfaces\Connection
     */
    public function connect(Interfaces\Element $element) : Interfaces\Connection {
        if (!$element instanceof Node) {
            throw new ElementConnectionException('EmptyFields can be connected only to Nodes');
        }
        return parent::connect($element);
    }

}