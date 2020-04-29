<?php

namespace Circuit\Structured; 

use Circuit\Interfaces;
use Circuit\Basic\Element;
use Circuit\Traits;

class EmptyField extends Element implements Interfaces\Particularity, Interfaces\Element\EmptyField {
    use Traits\IsEmptyField;

    /**
     * @property  string 
     */
    protected $description = 'Core representing element';

   
}