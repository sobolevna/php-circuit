<?php

namespace Circuit\Structured; 

use Circuit\Interfaces;
use Circuit\Traits;

class StructuredEmptyField extends StructuredElement implements Interfaces\Particularity, Interfaces\Element\EmptyField {

    use Traits\IsEmptyField;

    /**
     * @var  string 
     */
    protected $description = 'Particularity representing structured element';

    
}