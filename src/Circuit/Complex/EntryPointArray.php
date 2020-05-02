<?php

namespace Circuit\Complex; 

use Circuit\Interfaces;
use Circuit\Exceptions\ElementArrayException;

class EntryPointArray extends \ArrayObject implements Interfaces\Limitation {

    public function __construct(array $input) {
        foreach ($input as $item) {
            if (!(\is_object($item) && $item instanceof Interfaces\EntryPoint)) {
                throw new ElementArrayException();
            }
        }
        parent::__construct($input);
    }
}