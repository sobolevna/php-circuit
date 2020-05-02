<?php

namespace Circuit\Structured; 

use Circuit\Interfaces;
use Circuit\Traits;

class EmptyField extends Element implements Interfaces\Particularity, Interfaces\EmptyField {

    use Traits\IsEmptyField;

    /**
     * @var  string 
     */
    protected $description = 'Particularity representing structured element';

    
}