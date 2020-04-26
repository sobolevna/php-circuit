<?php

namespace Circuit\Basic; 

use Circuit\Interfaces;

class Limitation implements Interfaces\Limitation, Interfaces\Descriptable {

    use \Circuit\Traits\HasDescription;
    /**
     * @var  string 
     */
    protected $description = 'Everithing that is not it';
    
}