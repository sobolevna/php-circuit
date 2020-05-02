<?php

namespace Circuit\Complex; 

use Circuit\Interfaces;
use Circuit\Traits;

class ComplexEmptyField extends ComplexElement implements Interfaces\Particularity, Interfaces\Element\EmptyField {

    use Traits\IsEmptyField;

    /**
     * @var  string 
     */
    protected $description = 'Particularity representing complex element';
    
}