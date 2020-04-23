<?php 

namespace Circuit\Traits; 

use Circuit\Interfaces\{State, Handler};

trait IsState {
    /**
     * @var State 
     */
    protected $previousState;
    
    public function getPreviousState() : ?State {
        return $this->state;
    }

}