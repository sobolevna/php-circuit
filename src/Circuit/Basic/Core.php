<?php

namespace Circuit\Basic; 

use Circuit\Interfaces;
use Circuit\Traits\HasDescription;

class Core implements Interfaces\Core, Interfaces\Descriptable {

    use HasDescription;

    /**
     * @var  string 
     */
    protected $description = 'What exactly the entity is';

}