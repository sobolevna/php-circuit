<?php 

namespace Circuit\Traits; 

use Circuit\Interfaces\{State, Handler, Process};

trait IsProcess {
    /**
     * @var bool 
     */
    protected $finished = false;
    /**
     * @var State
     */
    protected $state;

    public function finish()  {
        $this->finished = true;
    }

    public function getState() : State {
        return $this->state;
    }

    public function process(Handler $handler) : Process {
        $this->state = $handler->handle($this->state);
        return $this;
    }
}