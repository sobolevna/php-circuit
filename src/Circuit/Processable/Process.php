<?php

namespace Circuit\Processable; 

use Circuit\Interfaces;
use Circuit\Traits\{IsState, HasDescription};

class Process implements Interfaces\Process, Interfaces\Descriptable {

    use IsProcess, HasDescription; 

    public function __construct() {
        $this->state = new State(null, 'Initial state');
    }    
    
}