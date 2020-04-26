<?php

namespace Circuit\Processable; 

use Circuit\Interfaces;
use Circuit\Traits\{IsState, HasDescription};

class State implements Interfaces\State, Interfaces\Descriptable {

    use IsState, HasDescription; 

    public function __construct(?Interfaces\State $previousState, string $description) {
        $this->previousState = $previousState;
        $this->description = $description;
    }
    
}