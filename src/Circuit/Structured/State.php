<?php 

namespace Circuit\Structured;

use Circuit\{Interfaces, Traits, Basic};

class State extends Basic\State implements Interfaces\Element {

    use Traits\IsElement;

    protected $description = 'A state that works like an element';

    protected $connectionClass = Basic\Connection::class;

    protected $previousState;

    public function __construct(?State $previousState = null) {
        $this->previousState = $previousState;
        if ($previousState) {
            $previousState->setImmutable();
            $this->connect($previousState);
        }        
    }

    public function setMergedState(State $state) {
        if ($this->mergedState) {
            throw new ProcessEsception('This state is already a merge result');
        }
        $this->mergedState = $state;
        $this->connect($state);
        $this->setImmutable();
        $this->mergedState->setImmutable();
    }

    public function getMergedState() : State {
        return $this->mergedState;
    }
}