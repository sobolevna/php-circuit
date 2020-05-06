<?php 

namespace Circuit\Traits; 

use Circuit\Interfaces\{State, Handler, Process};
use Circuit\Exceptions\ProcessException;

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

    public function isFinished() : bool {
        return $this->finished;
    }

    public function getState() : State {
        return $this->state;
    }

    public function process(Handler $handler, array $params = []) : Process {
        if ($this->finished) {
            throw new ProcessException('The process has already been finished');
        }
        $this->state = $handler->handle($this->state, $params);
        return $this;
    }
}