<?php 

namespace Circuit\Basic;

use Circuit\{Interfaces, Traits};

class State implements Interfaces\State, Interfaces\Descriptable {

    use Traits\IsState, Traits\HasDescription;

    protected $description = 'Basic state';

    protected $previousState;

    public function __construct(?Interfaces\State $previousState = null) {
        $this->previousState = $previousState;
        if ($previousState) {
            $previousState->setImmutable();
        }
        
    }
}