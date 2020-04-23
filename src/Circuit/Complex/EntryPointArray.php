<?php

namespace Circuit\Implementation\Basic; 

use Circuit\Interfaces;
use Circuit\Exceptions\ElementArrayException;

class EntryPointArray extends \ArrayObject implements Interfaces\Limitation {

    public function __construct(array $input) {
        foreach ($input as $item) {
            if (!(\is_object($item) && $item instanceof Interfaces\Element\EntryPoint)) {
                throw new ElementArrayException();
            }
        }
        parent::construct($input);
    }
}