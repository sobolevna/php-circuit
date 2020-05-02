<?php

namespace Circuit\Basic; 

use Circuit\Interfaces;
use Circuit\Basic\Element;
use Circuit\Traits;

class EmptyField extends Element implements Interfaces\Particularity, Interfaces\EmptyField {
    use Traits\IsEmptyField;

    /**
     * @var  string 
     */
    protected $description = 'Core representing element';

   
}