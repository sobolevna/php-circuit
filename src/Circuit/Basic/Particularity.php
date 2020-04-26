<?php

namespace Circuit\Basic; 

use Circuit\Interfaces;

class Particularity implements Interfaces\Particularity {

    use \Circuit\Traits\HasDescription;
    /**
     * @var  string 
     */
    protected $description = 'What differs it from others';
    
}