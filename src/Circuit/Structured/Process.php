<?php 

namespace Circuit\Structured;

use Circuit\{Interfaces, Traits, Basic};

class Process extends Basic\Process implements Interfaces\Structure {

    use Traits\IsStructured;

    protected $description = 'Structured process with states as its elements. Allows branching and merging';

    public function __construct(State $state) {
        parent::__construct($state);
        $this->structureElements[] = $state;
    }

    public function process(Interfaces\Handler $handler, array $params = []) : Interfaces\Process {
        $previousState = $this->state;
        parent::process($handler);
        $this->structureElements[] = $this->state;
        $this->structureConnections[] = current($this->state->getConnections());
        return $this;
    }

    public function branch(?State $state = null) {
        if ($state && !in_array($state, $this->structureElements, true)) {
            throw new ProcessException('You are trying to branch from a state that is not in the process');            
        }
        $state = $state ?? $this->state;
        return new static($state);
    }

    public function mergeProcess(Handler $handler, Process $process) {
        $process->finish();
        return $this->mergeState($handler, $process->getState());
    }

    public function mergeState(Handler $handler, State $stateToMerge) {
        $newState = $handler->handle($this->state, [$stateToMerge]);
        $this->checkMergeResult($this->state, $stateToMerge, $newState);
        $this->state = $newState;
        $this->structureElements[] = $this->state;
        $this->structureElements[] = $stateToMerge;
        $this->structureConnections = array_merge($this->structureConnections, $this->state->getConnections(), $stateToMerge->getConnections());
        return $this;
    }

    protected function checkMergeResult(State $originalState, State $stateToMerge, State $newState) {
        if ($stateToMerge !== $newState->getMergedState()) {
            throw new ProcessException('New state doesn\'t have a merged state');            
        }
        $connectedElements = [];
        foreach ($newState->getConnections() as $connection) {
            foreach ($connection->getElements() as $element) {
                $connectedElements[] = $element;
            }
        }
        if (!in_array($stateToMerge, $connectedElements, true)) {
            throw new ProcessException('New state is not connected to the merged one');            
        }
        if (!in_array($originalState, $connectedElements, true)) {
            throw new ProcessException('Original state is not connected to the new one');            
        }
    }
}