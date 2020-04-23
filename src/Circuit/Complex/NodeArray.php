<?php

namespace Circuit\Basic; 

use Circuit\Interfaces;
use Circuit\Exceptions\ElementArrayException;

class NodeArray extends \ArrayObject implements Interfaces\Core {

    public function __construct(array $input) {
        foreach ($input as $item) {
            if (!(\is_object($item) && $item instanceof Interfaces\Element\Node)) {
                throw new ElementArrayException();
            }
        }
        parent::construct($input);
    }
}