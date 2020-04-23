<?php

namespace Circuit\Implementation\Basic; 

use Circuit\Interfaces;
use Circuit\Exceptions\ElementArrayException;

class EmptyFieldArray extends \ArrayObject implements Interfaces\Particularity {

    public function __construct(array $input) {
        foreach ($input as $item) {
            if (!(\is_object($item) && $item instanceof Interfaces\Element\EmptyField)) {
                throw new ElementArrayException();
            }
        }
        parent::construct($input);
    }
}