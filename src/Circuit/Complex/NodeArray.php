<?php

namespace Circuit\Complex; 

use Circuit\Interfaces;
use Circuit\Exceptions\ElementArrayException;

class NodeArray extends \ArrayObject implements Interfaces\Core {

    public function __construct(array $input) {
        foreach ($input as $item) {
            if (!(\is_object($item) && $item instanceof Interfaces\Node)) {
                throw new ElementArrayException();
            }
        }
        parent::__construct($input);
    }
}