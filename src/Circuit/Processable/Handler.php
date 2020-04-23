<?php

namespace Circuit\Processable; 

use Circuit\Interfaces;
use Circuit\Traits\{IsState, HasDescription};

class Handler implements Interfaces\Handler, Interfaces\Descriptable {

    use IsHandler, HasDescription; 

    protected $description = 'A simple handler: it changes the description to the current date';
    
    public function handle(Interfaces\State $state) : Interfaces\State {
        return new State($state, \date('c'));
    }
}